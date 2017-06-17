/**
* @author    JoomlaShine.com http://www.joomlashine.com
* @copyright Copyright (C) 2008 - 2011 JoomlaShine.com. All rights reserved.
* @license   GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
*/

var JSNSimpleLoadingStatus = {
	start: function(text_span) {
		var textArray = ["...still in progress", "...please wait"];
		var count = -1;

		this.textEl = text_span;
		this.textEl.removeClass("jsn-installsample-non-display")
			.addClass("jsn-installsample-display-inline");

		this.timer = setInterval(
			function() {
				text_span.set("html", textArray[++count % textArray.length]);
			}, 5000);
		return this;
	},

	clear: function() {
		clearInterval(this.timer);
		this.textEl.set("html", "")
			.removeClass("jsn-installsample-display-inline")
			.addClass("jsn-installsample-non-display");
	}
};

var JSNSampleData = {

	skipFlag: false,
	skipPoint: '',
	currentExt: '',

	init: function() {},

	start: function(templateName, url, styleId)
	{
		$('jsn-installing-sampledata').addClass('jsn-installsample-display-block');

		JSNSampleData.downloadPackage(templateName, url, styleId);
	},

	downloadPackage: function(templateName, url, styleId)
	{
		/* Show loading icon */
		var stepLi = $("jsn-download-sample-data-package-title");

		var spanTitle    = stepLi.getChildren("span.jsn-step-subtitle");
		var spanState    = stepLi.getChildren("span.jsn-step-state-indicator");
		spanState.addClass("jsn-sampledata-icon-small-loader").addClass("jsn-installsample-display-inline");

		var jsonRequest = new Request.JSON(
		{
			url: url + "index.php",
			onSuccess: function(jsonObj)
			{
				if(jsonObj.download)
				{
					/* Show success icon, strikethrough the text */
					spanTitle.addClass("jsn-successful-subtitle");
					spanState.removeClass("jsn-sampledata-icon-small-loader").addClass("jsn-sampledata-icon-small-successful");

					/* Tell user to choose which extensions to be installed */
					if (jsonObj.exts)
					{
						/* Show the selection section */
						$("jsn-extension-selection").removeClass("jsn-installsample-non-display");

						var extSelectionHtml = '<ul>';
						Object.each(jsonObj.exts, function(item, key)
						{
							extSelectionHtml += '<li><input id="'+key+'" type="checkbox" name="installExts" value="'+key+'" checked="checked" />';
							extSelectionHtml += '<label for="'+key+'">'+item.desc+'</label>';
							if (item.message != "")
							{
								extSelectionHtml += '&nbsp;<span class="jsn-green-message"> - '+item.message+'</span></li>';
							}
							else{
								extSelectionHtml += '</li>';
							}
						});
						extSelectionHtml += '</ul>';

						$("jsn-extension-list").set("html", extSelectionHtml);

						$("jsn-install-continue-button").addEvent("click", function(e)
						{
							e.preventDefault();
							JSNSampleData.selectExtensions(templateName, url, styleId);
						});
					}
					else
					{
						/* Go directly to install sample data */
						JSNSampleData.installSampleData(templateName, jsonObj.sampleDataFile, url);
					}
				}
				else
				{
					/* Show error icon, error message */
					spanState.removeClass("jsn-sampledata-icon-small-loader").addClass("jsn-sampledata-icon-small-error");
					stepLi.getChildren("span.jsn-step-message")
						.set("html", jsonObj.message)
						.addClass("jsn-error-message")
						.addClass("jsn-installsample-display-inline");

					$("jsn-install-cancel").addClass("jsn-installsample-display-block");

					if (!jsonObj.connection)
					{
						$("jsn-install-sample-data-manually-inline").addClass("jsn-installsample-display-block");
					}
				}
			}
		}).get({"template": templateName, "tmpl": "jsn_runajax", "task": "downloadSampleDataPackage", "template_style_id": styleId});
	},

	selectExtensions: function(templateName, url, styleId)
	{
		var exts = $$("input[name=installExts]:checked").get('value');
		if (exts.length === 0)
		{
			exts = [];
		}

		var jsonRequest = new Request.JSON(
		{
			url: url + "index.php",
			onSuccess: function(jsonObj)
			{
				/* Hide the selection section */
				$("jsn-extension-selection").addClass("jsn-installsample-non-display");

				if (jsonObj.exts && Object.getLength(jsonObj.exts) > 0)
				{
					var listHtml = "<ul>";

					Object.each(jsonObj.exts, function(item, key)
					{
						listHtml += JSNSampleData.populateExtListItem(key, item.desc);

						if (item.deps)
						{
							listHtml += "<ul>";
							Object.each(item.deps, function(item, key)
							{
								listHtml += JSNSampleData.populateExtListItem(key, item.desc);
								listHtml += "</li>";
							});
							listHtml += "</ul>";
						}

						listHtml += "</li>";
					});
					listHtml += "</ul>";

					$("jsn-install-extension-sublist").set("html", listHtml);

					/* Change to extension installation */
					$("jsn-install-extensions-title").addClass("jsn-installsample-display-list");
					JSNSampleData.installExtension(templateName, url, styleId, jsonObj.firstExt, jsonObj.childOf, jsonObj.isLastExt, jsonObj.sampleDataFile);
				}
				else
				{
					/* Go directly to install sample data */
					JSNSampleData.installSampleData(templateName, jsonObj.sampleDataFile, url);
				}
			}
		}).get({"template": templateName, "tmpl": "jsn_runajax", "task": "selectExtensions", "template_style_id": styleId, 'exts': exts});
	},

	installExtension: function(templateName, url, styleId, extId, childOf, isLastExt, sampleDataFile)
	{
		var self = this;

		var stepLi = $("jsn-install-extensions-title");

		/* First, get loading icon appears for currently-installed ext row */
		var subStepLi = $("jsn-install-extension-" + extId);
		var spanTitle = subStepLi.getChildren("span.jsn-step-subtitle");
		var spanState = subStepLi.getChildren("span.jsn-step-state-indicator");
		var spanProgress = subStepLi.getChildren("span.jsn-progress-message");
		spanState.addClass("jsn-sampledata-icon-small-loader")
			.addClass("jsn-installsample-display-inline");

		/* Start the timer to display loop of "in-progress" messages */
		var loadingStatus = JSNSimpleLoadingStatus.start(spanProgress);

		/* Start the timer (only for "parent" ext) to display skip button */
		var skipTimer = null;
		if (childOf == "" && isLastExt === false)
		{
			skipTimer = setTimeout(function() {
				$("jsn-skip-install-ext-wrapper").addClass("jsn-installsample-display-block");
			}, 30000);
		}

		/* Save currently installed ext */
		this.currentExt = extId;

		/* Next, send request to install the extension to server */
		var link = url + 'index.php';
		var jsonRequest = new Request.JSON({
			url: link,
			onSuccess: function(jsonObj)
			{
				loadingStatus.clear();

				if (skipTimer !== null)
				{
					clearTimeout(skipTimer);
				}

				/* Processing of children exts were finished */
				if (jsonObj.childOf == "")
				{
					$("jsn-skip-install-ext-wrapper").removeClass("jsn-installsample-display-block");
				}

				if (jsonObj.tocontinue)
				{
					if (jsonObj.installExt)
					{
						/* Show success icon, strikethrough the text */
						spanTitle.addClass("jsn-successful-subtitle");
						spanState.removeClass("jsn-sampledata-icon-small-loader")
							.addClass("jsn-sampledata-icon-small-successful");
					}
					else
					{
						/* Show error icon */
						spanState.removeClass("jsn-sampledata-icon-small-loader")
							.addClass("jsn-sampledata-icon-small-error");

						subStepLi.getChildren("span.jsn-step-message")
							.set("html", jsonObj.message)
							.addClass("jsn-error-message")
							.addClass("jsn-installsample-display-inline");
					}

					/* Process the next extensions for moving to next step */
					if (jsonObj.nextExt != "")
					{
						if (self.skipFlag === false)
						{
							JSNSampleData.installExtension(templateName, url, styleId, jsonObj.nextExt, jsonObj.childOf, jsonObj.isLastExt, sampleDataFile);
						}
						else if (jsonObj.childOf == self.skipPoint)
						{
							JSNSampleData.installExtension(templateName, url, styleId, jsonObj.nextExt, jsonObj.childOf, jsonObj.isLastExt, sampleDataFile);
						}
						else
						{
							/* Strikethrough the "parent" title */
							stepLi.getChildren("span.jsn-step-subtitle")
								.addClass("jsn-successful-subtitle");

							JSNSampleData.installSampleData(templateName, sampleDataFile, url);
						}
					}
					else
					{
						/* Strikethrough the "parent" title */
						stepLi.getChildren("span.jsn-step-subtitle")
							.addClass("jsn-successful-subtitle");

						JSNSampleData.installSampleData(templateName, sampleDataFile, url);
					}
				}
			}
		})
		.get({"template": templateName, "tmpl": "jsn_runajax", "task": "requestInstallExtension", "ext_name": extId});

		/**
		 * Handle Skip Extension Installation button
		 * Skip button will continue current install process and its children
		 * but halt all further installations.
		 */
		$("jsn_skip_install_ext").addEvent("click", function() {
			var c = confirm('Please wait until current processes are finished.\r\n\r\nAre you sure you want to skip extension installation process and pass to the next stage?');
			if (c === true)
			{
				self.skipFlag = true;
				self.skipPoint = self.currentExt;

				/* Disable and change text of skip button */
				this.set("text", "Please wait until current processes are finished.");
				this.disabled = true;

				/* Add class "jsn-noskip" to current ext & its children's state span */
				subStepLi.getElements("span.jsn-step-state-indicator").addClass("jsn-noskip");

				/* Display error icon for skipped exts */
				$("jsn-install-extension-sublist").getElements("span.jsn-step-state-indicator:not(.jsn-noskip)")
					.addClass("jsn-sampledata-icon-small-error")
					.addClass("jsn-installsample-display-inline");
			}
		});
	},

	installSampleData: function(templateName, file_name, url)
	{
		/* Show loading icon */
		var stepLi = $("jsn-install-sample-data-package-title");
		stepLi.addClass("jsn-installsample-display-list");

		var spanTitle = stepLi.getChildren("span.jsn-step-subtitle");
		var spanState = stepLi.getChildren("span.jsn-step-state-indicator");
		spanState.addClass("jsn-sampledata-icon-small-loader")
			.addClass("jsn-installsample-display-inline");

		var jsonRequest = new Request.JSON(
		{
			url: url + "index.php",
			onSuccess: function(jsonObj)
			{
				if(jsonObj.install)
				{
					/* Show success icon, strikethrough the text */
					spanTitle.addClass("jsn-successful-subtitle");
					spanState.removeClass("jsn-sampledata-icon-small-loader")
						.addClass("jsn-sampledata-icon-small-successful");

					/* Display green success message block */
					$("jsn-installing-sampledata-successfully").addClass("jsn-installsample-display-block");

					if (jsonObj.warnings.length)
					{
						$("jsn-warnings").addClass("jsn-installsample-display-block");
						JSNSampleData.renderWarning(jsonObj.warnings);
					}
				}
				else
				{
					/* Show error icon, error message */
					spanState.removeClass("jsn-sampledata-icon-small-loader").addClass("jsn-sampledata-icon-small-error");
					stepLi.getChildren("span.jsn-step-message")
						.set("html", jsonObj.message)
						.addClass("jsn-error-message")
						.addClass("jsn-installsample-display-inline");

					$("jsn-install-cancel").addClass("jsn-installsample-display-block");

					if (jsonObj.manual != undefined)
					{
						if (jsonObj.manual)
						{
							$("jsn-install-sample-data-manually-inline").addClass("jsn-installsample-display-block");
						}
					}
				}
			}
		}).get({"template": templateName, "tmpl": "jsn_runajax", "task": "installSampleData", "file_name": file_name});
	},

	renderWarning: function(data)
	{
		var warnings = data;
		var count	 = warnings.length;
		var ul 		 = $("jsn-ul-warnings");
		if (count)
		{
			for(var i=0; i < count; i++)
			{
				var li = new Element("li", {html: warnings[i]});
				li.inject(ul);
			}
		}
	},

	setInstallationButtonState: function (form)
	{
		var username = form.username.value;
		var password = form.password.value;
		var agree = form.agree.checked;
		if (username != '' && password != '' && agree)
		{
			form.installation_button.disabled = false;
		}
		else
		{
			form.installation_button.disabled = true;
		}
	},

	setInlineInstallationButtonState:function(form)
	{
		if (form.install_package.value == "")
		{
			form.jsn_inline_install_manual_button.disabled = true;
		}
		else
		{
			form.jsn_inline_install_manual_button.disabled = false;
		}
	},

	/**
	 * Please remember to add '</li>' to the result of this function. This will
	 * be addressed later for more convenient use of code.
	 */
	populateExtListItem: function(id, desc)
	{
		var liHtml = "";

		liHtml += "<li id=\"jsn-install-extension-" + id + "\">";
		liHtml += "<span class=\"jsn-step-subtitle\">" + desc + "</span>";
		liHtml += "\r\n<span class=\"jsn-step-state-indicator\"></span>";
		liHtml += "\r\n<span class=\"jsn-progress-message\"></span>";
		liHtml += "\r\n<span class=\"jsn-step-message\"></span>";

		return liHtml;
	},

	/**
	 * This function return "identifier name" for an extension or template by
	 * converting all characters to lowercase and replacing (1 or more)
	 * whitespace(s) by "-" character.
	 */
	populateIdentifierName: function(name)
	{
		return name.toLowerCase().replace(/\s+/g, "-");
	},

	checkFolderPermission: function(templateName, url)
	{
		var folderListElement = $("jsn-li-folder-perm-failed");

		var checkAgainButton = $("jsn-perm-try-again");
		/* Disable the button */
		checkAgainButton.disabled = true;

		var jsonRequest = new Request.JSON(
		{
			url: url + "index.php",
			onSuccess: function(jsonObj) {
				if(!jsonObj.permission)
				{
					/* Display a list of un-writable folders */
					var ul = folderListElement.getChildren("ul");

					var count = jsonObj.folders.length;
					var liHtml ="";
					Array.each(jsonObj.folders, function(item)
					{
						liHtml += "<li>" + item + "</li>";
					});

					ul.set("html", liHtml);

					/* Disable form inputs */
					JSNSampleData.disableFormInputs($("frm-login"), true);

					/* Hide the form */
					$("jsn-auto-install-login-form").addClass("jsn-installsample-non-display");

					/* Enable the check button again */
					checkAgainButton.disabled = false;
				}
				else
				{
					/* Remove the list */
					folderListElement.getChildren("ul").set("html", "");
					folderListElement.removeClass("jsn-installsample-display-list")
						.addClass("jsn-installsample-non-display");

					/* Enable form inputs */
					JSNSampleData.disableFormInputs($("frm-login"), false);

					/* Show the form */
					$("jsn-auto-install-login-form").removeClass("jsn-installsample-non-display")
						.addClass("jsn-installsample-display-block");
				}
			}
		}).get({"template": templateName, "tmpl": "jsn_runajax", "task": "checkFolderPermission"});
	},

	disableFormInputs: function(form, state)
	{
		form.username.disabled = state;
		form.password.disabled = state;
		form.agree.disabled = state;
	},

	disableModalCloseButton: function()
	{
		var closeButton = window.parent.document.getElementById("sbox-btn-close");
		if (closeButton && closeButton.parentNode) {
			closeButton.parentNode.removeChild(closeButton);
		}
	}
};