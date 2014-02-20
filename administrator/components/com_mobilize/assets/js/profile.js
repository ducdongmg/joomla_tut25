define([
    'jquery',
    'jsn/libs/modal',
    'mobilize/style',
    'mobilize/dialogedition',
    'jquery.tipsy',
    'jquery.ui',
    'jquery.cookie',
    'jquery.json'
],
    function ($, JSNModal, JSNStyle, JSNMobilizeDialogEdition) {
        var JSNMobilizeProfileView = function (params) {
            $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
            $("body").append($("<div/>", {
                "class":"jsn-modal-overlay",
                "style":"z-index: 1000; display: inline;"
            })).append($("<div/>", {
                "class":"jsn-modal-indicator",
                "style":"display:block"
            })).addClass("jsn-loading-page");
            this.params = params;
            this.lang = params.language;
            this.listMenu = params.listMenu;
            this.listModule = params.listModule;
            this.defaultTemplate = params.defaultTemplate;
            this.pathRoot = params.pathRoot;
            this.configuration = params.configuration;
            this.init();
            // $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
        }
        var updateItem = false;
        var listModuleInPosition = [];
        JSNMobilizeProfileView.prototype = {
            init:function () {
                this.JSNMobilizeDialogEdition = new JSNMobilizeDialogEdition(this.params);
                this.JSNStyle = new JSNStyle(this.params);
                var self = this;
                var edition = this.params.editions;
                var mobilizeSelectedTab = 0;
                if ($.cookie('jsn_mobilize_tabs_' + $("#jform_profile_id").val()) == "li-design") {
                    mobilizeSelectedTab = 1;
                }
                $(".jsn-tabs").tabs({
                    selected:mobilizeSelectedTab,
                    select:function (event, ui) {
                        $.cookie('jsn_mobilize_tabs_' + $("#jform_profile_id").val(), '', {
                            expires:-1
                        });
                        $.cookie('jsn_mobilize_tabs_' + $("#jform_profile_id").val(), $(ui.tab).attr("id"));
                    }
                });
                $('.jsn-tipsy').tipsy({
                    gravity:'w',
                    fade:true
                });
                this.registerEvent();
                $("a.jsn-add-more").click(function (e) {
                    var thisParents = $(this).parents('.jsn-column-container').attr("id");
                    if (edition.toLowerCase() != "free") {
                        self.addElement($(this));
                        $(".ui-state-edit").removeClass("ui-state-edit");
                        e.stopPropagation();
                    } else {
                        if (thisParents != "jsn-content-top" && thisParents != "jsn-user-top" && thisParents != "jsn-user-bottom" && thisParents != "jsn-content-bottom") {
                            self.addElement($(this));
                            $(".ui-state-edit").removeClass("ui-state-edit");
                            e.stopPropagation();
                        } else {
                            JSNMobilizeDialogEdition.createDialogLimitation($(this), self.lang["JSN_MOBILIZE_ADD_ELEMENT_IS_AVAILABLE_ONLY_IN_PRO_EDITION"]);
                        }
                    }
                });
                $("#jsn-logo a.element-edit").click(function (e) {
                    self.createPopupDialogLogo($(this));
                    e.stopPropagation();
                });
                $("ul.mobilize-menu li a").click(function (e) {
                    self.createPopupDialogMenuIcon($(this));
                    e.stopPropagation();
                });
                $("#jsn-switcher button.btn-switcher").click(function (e) {
                    self.createPopupDialogMenuIcon($(this));
                    e.stopPropagation();
                });
                $.getModuleList = function () {
                    return listModuleInPosition;
                };
                $.setModuleList = function (modules) {
                    listModuleInPosition = modules;
                };
                $("#design .jsn-mobilize .btn-group.jsn-inline .mobilize_view_layout").click(function () {
                    if (!$(this).hasClass("active")) {
                        $("#design .jsn-mobilize .btn-group.jsn-inline .mobilize_view_layout").removeClass("active");
                        $(this).addClass("active");
                        self.changeViewMobilize();
                    }
                });
                this.changeViewMobilize('default');
                window.changeLogo = function (src, modal) {
                    var dataInput = {};
                    var mobilizeLogo = $("#jsn-logo");
                    $(".mobilize-dialog .popover-content input.logo-url").val(src);
                    dataInput[src] = $(".mobilize-dialog .popover-content input.logo-alt").val();
                    mobilizeLogo.find("input.data-mobilize").val($.toJSON(dataInput));
                    mobilizeLogo.find("input.data-mobilize").attr("data-id", src);
                    if (modal !== false) {
                        mobilizeLogo.find("img").attr("src", self.pathRoot + src).load(function () {
                            $("#jsn-logo a.element-edit").click();
                        });
                        mobilizeLogo.find("span.jsn-select-logo").hide();
                        $("#jsn-logo a.element-edit").removeClass("jsn-logo-null");
                        self.modalchangeLogo.close();
                    } else {
                        mobilizeLogo.find("span.jsn-select-logo").show();
                        mobilizeLogo.find("img").attr({"src":"", "alt":""});
                        $("#jsn-logo a.element-edit").addClass("jsn-logo-null").click();
                    }
                };
                $.jSelectPosition = function (postion) {
                    var options = new Object();
                    options.value = postion;
                    options.type = "position";
                    options.name = postion;
                    options.label = self.lang["JSN_MOBILIZE_TYPE_POSITION"];
                    self.saveItem(options);
                    self.closeModalBox();
                    $(".mobilize-dialog").remove();
                };
                $.changeModuleMenuIcon = function (id, title) {
                    self.changeModuleMenuIcon(id, title);
                };
                $.jSelectModules = function (id, title, action) {
                    var options = new Object();
                    options.value = id;
                    options.type = "module";
                    options.name = title;
                    options.label = self.lang["JSN_MOBILIZE_TYPE_MODULE"];
                    self.saveItem(options);
                    if (action == "update") {
                        self.closeModalBox();
                    }
                    $(".mobilize-dialog").remove();
                };
                $("button.mobilize-preview").click(function () {
                    $(document).trigger("click");
                    if ($(this).hasClass("active")) {
                        $("#mobilize-design #jsn-mobilize").show();
                        $("#mobilize-design .jsn-mobilize-preview").remove();
                        $("#mobilize-design .jsn-bgloading").remove();
                        $(this).removeClass("active");
                        $(this).text('');
                        $(this).append('<i class="icon-eye-open"></i>' + $(this).attr("text-enable"));
                    } else {
                        $("body").append($("<div/>", {
                            "class":"jsn-modal-overlay",
                            "style":"z-index: 1000; display: inline;"
                        })).append($("<div/>", {
                            "class":"jsn-modal-indicator",
                            "style":"display:block"
                        })).addClass("jsn-loading-page");
                        $(this).addClass("active");
                        $(this).text('');
                        $(this).append('<i class="icon-eye-open"></i> ' + $(this).attr("text-disable"));
                        self.createPreview();
                    }
                    return false;
                });
                $(document).click(function () {
                    $(".mobilize-dialog").remove();
                    $(".jsn-element-active").removeClass("jsn-element-active");
                    $(".ui-state-edit").removeClass("ui-state-edit");
                });
                Joomla.submitbutton = function (pressedButton) {
                    if (/^profile\.(save|apply)/.test(pressedButton)) {
                        if ($("#jform_profile_title").val() == "") {
                            $(".jsn-tabs").tabs({
                                selected:0
                            });
                            $("#jform_profile_title").parent().parent().addClass("error");
                            $("#jform_profile_title").focus();
                            alert('Please correct the errors in the Form');
                            return false;
                        }
                    }
                    submitform(pressedButton);
                };
                $("#select_profile_style").click(function (e) {
                    self.dialogLoadStyleProfile($(this));
                    e.stopPropagation();
                });
                this.setDefaultCss();
                setTimeout(function () {
                    $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
                    $(".jsn-mobilize-form").removeClass("hide");
                }, 500);
                $(".jsn-iconbar-trigger .jsn-iconbar a.element-delete").click(function () {
                    self.JSNMobilizeDialogEdition = new JSNMobilizeDialogEdition(self.params);
                    JSNMobilizeDialogEdition.createDialogLimitation($(this), self.lang["JSN_MOBILIZE_YOU_CAN_NOT_HIDE_THE_COPYLINK"]);
                    return false;
                });

            },
            setDefaultCss:function () {
                var self = this;
                $("#jsn-mobilize .jsn-iconbar a").each(function () {
                    self.JSNStyle.changeStyle($(this));
                });
            },
            dialogLoadStyleProfile:function (_this) {
                var self = this;
                var dialog = $("#container-select-style"), parentDialog = $("#container-select-style").parent();
                var styleList = this.JSNStyle.profileStyleList();
                $(dialog).find("#profile-style-list").empty();
                $.each(styleList, function (i, val) {
                    $(dialog).find("#profile-style-list").append(
                        $("<div/>", {"class":"jsn-column-item"}).append(
                            $("<a/>", {"class":"thumbnail", "href":"javascript:void(0);"}).append(
                                $("<img/>", {"src":val.thumbnail, "alt":val.title})
                            ).append(
                                $("<div/>", {"class":"caption"}).append(
                                    $("<h3/>").append(val.title)
                                )
                            ).click(function () {
                                    if (confirm(self.lang['JSN_MOBILIZE_CONFIRM_LOAD_STYLE'])) {
                                        $.each(val.style, function (j, k) {
                                            $("#input_style_" + j).val(k);
                                            self.JSNStyle.changeStyle($("#input_style_" + j));
                                        });
                                        $(dialog).hide();
                                    }
                                })
                        )
                    )
                });
                dialog.width("600");
                $(dialog).appendTo('body');
                var elmStyle = self.getBoxStyle($(dialog)),
                    parentStyle = self.getBoxStyle($(_this)),
                    position = {};
                position.left = parentStyle.offset.left - elmStyle.outerWidth + parentStyle.outerWidth;
                position.top = parentStyle.offset.top + parentStyle.outerHeight;
                $(dialog).find(".arrow").css("left", elmStyle.outerWidth - (parentStyle.outerWidth / 2));
                dialog.css(position).click(function (e) {
                    e.stopPropagation();
                });
                $(dialog).show();
                $("#container-select-style .popover").show();
                $(document).click(function (e) {
                    $(dialog).hide();
                });
            },
            changeViewMobilize:function (load) {
                var self = this,
                    cookieView = $.cookie('jsn_profile_' + $("#jform_profile_id").val());
                if (load == "default") {
                    if (cookieView && $("#jform_profile_id").val()) {
                        if (cookieView == "tablet_ui_enabled") {
                            $("#mobile_ui_enabled").removeClass("active");
                            $("#tablet_ui_enabled").addClass("active");
                        } else {
                            $("#mobile_ui_enabled").addClass("active");
                            $("#tablet_ui_enabled").removeClass("active");
                        }
                    } else {
                        $("#mobile_ui_enabled").addClass("active");
                        $("#tablet_ui_enabled").removeClass("active");
                    }
                }
                $("#design .jsn-mobilize .btn-group.jsn-inline .mobilize_view_layout").each(function () {
                    if ($(this).hasClass("active")) {
                        if ($(this).attr("id") == "mobile_ui_enabled") {
                            $("#mobilize .mobilize-title.jsn-section-header h1").text(self.lang['JSN_MOBILIZE_TITLE_SMARTPHONE']);
                            $("#jsn-mobilize").addClass("jsn-layout-mobile");
                            $("#mobilize").animate({
                                width:"550px"
                            }, "easein");
                        } else {
                            $("#mobilize .mobilize-title.jsn-section-header h1").text(self.lang['JSN_MOBILIZE_TITLE_TABLET']);
                            $("#jsn-mobilize").removeClass("jsn-layout-mobile");
                            $("#jsn-mobilize").removeClass("jsn-layout-mobile");
                            $("#mobilize").animate({
                                width:"850px"
                            }, "easein");
                        }
                        $.cookie('jsn_profile_' + $("#jform_profile_id").val(), '', {
                            expires:-1
                        });
                        $.cookie('jsn_profile_' + $("#jform_profile_id").val(), $(this).attr("id"));
                    }
                });
            },
            changeSwitcherSettings:function (title) {
                if (!title) {
                    title = "Switch to Desktop";
                }
                var dataInput = {};
                var dataCheckEnable = $(".mobilize-dialog .radio input.jsn-check-enable:checked").val();
                $("#jsn-switcher button.btn-switcher").attr("data-value", title);
                $("#jsn-switcher button.btn-switcher").attr("data-state", dataCheckEnable);
                $("#jsn-switcher button.btn-switcher").html(title);
                $("#jsn-switcher input.data-mobilize").attr("data-id", title);
                dataInput[title] = dataCheckEnable;
                $("#jsn-switcher input.data-mobilize").val($.toJSON(dataInput));
            },
            //Change Module Menu Popup
            changeModuleMenuIcon:function (id, title) {
                var menuChangeModule = $(".mobilize-menu .mobilize-edit").parent();
                var dataInput = {};
                menuChangeModule.find("a.link-menu-mobilize").attr("data-value", id);
                menuChangeModule.find("input.data-mobilize").attr("data-id", id);
                dataInput[id] = title;
                menuChangeModule.find("input.data-mobilize").val($.toJSON(dataInput));
                $(".mobilize-menu .mobilize-edit").click();
                this.closeModalBox();
            },
            addElement:function (_this) {
                $(".mobilize-dialog").remove();
                $(".mobilize-list-edit").removeClass("mobilize-list-edit");
                $(_this).parent().parent().addClass("mobilize-list-edit");
                var self = this;
                // create html dialog container
                var dialog = $("<div/>", {"class":"mobilize-dialog jsn-bootstrap"}).append(
                    $("<div/>", {"class":"popover top"}).css("display", "block").append(
                        $("<div/>", { "class":"arrow" })).append(
                        $("<h3/>", { "class":"popover-title", "text":"Select Element"})).append(
                        $("<div/>", {"class":"popover-content" }).append(
                            $("<div/>", { "class":"jsn-columns-container jsn-columns-count-two" }).append(
                                $("<div/>", {"class":"jsn-mobilize-element" }).append(
                                    $("<div/>", { "class":"jsn-column-item"}).append(
                                        $("<div/>", { "class":"jsn-element-module" }).append(
                                            $("<button/>", {"class":"btn", "name":self.lang['JSN_MOBILIZE_ADD_MODULE'], "text":self.lang['JSN_MOBILIZE_ADD_MODULE']}).click(function () {
                                                updateItem = false;
                                                listModuleInPosition = [];
                                                $(".mobilize-list-edit .jsn-element-container div.jsn-element").each(function () {
                                                    if ($(this).attr("data-type") == "module") {
                                                        var optionsModule = new Object();
                                                        optionsModule.value = $(this).attr("data-value");
                                                        optionsModule.name = $(this).find("span.name-jsn-element").html();
                                                        listModuleInPosition.push(optionsModule);
                                                    }
                                                });
                                                self.actionAddModule('new');
                                                return false;
                                            })))).append(
                                    $("<div/>", { "class":"jsn-column-item"}).append(
                                        $("<div/>", {"class":"jsn-element-position" }).append(
                                            $("<button/>", { "class":"btn", "title":self.lang['JSN_MOBILIZE_ADD_POSITION'], "text":self.lang['JSN_MOBILIZE_ADD_POSITION']}).click(function () {
                                                updateItem = false;
                                                self.actionAddPosition();
                                                return false;
                                            })
                                        )
                                    )
                                )
                            )
                        )
                    )
                );
                $("body").append(dialog);
                var elmStyle = self.getBoxStyle($(dialog).find(".popover")),
                    parentStyle = self.getBoxStyle($(_this)),
                    position = {};
                position.left = parentStyle.offset.left - elmStyle.outerWidth / 2 + parentStyle.outerWidth / 2;
                position.top = parentStyle.offset.top - elmStyle.outerHeight;
                dialog.css(position).click(function (e) {
                    e.stopPropagation();
                });
            },
            actionChangeLogo:function (_this, mobilizeMenuItem) {
                var dataInput = {};
                dataInput[mobilizeMenuItem.find("input.data-mobilize").attr("data-id")] = $(_this).val();
                mobilizeMenuItem.find("a.element-edit").attr("data-state", $(_this).val());
                if ($(_this).val()) {
                    mobilizeMenuItem.find("img").attr("alt", $(_this).val());
                } else {
                    mobilizeMenuItem.find("img").attr("alt", "Select Logo");
                }
                mobilizeMenuItem.find("input.data-mobilize").val($.toJSON(dataInput));
            },
            createPopupDialogLogo:function (_this) {
                $(".mobilize-dialog").remove();
                $(".mobilize-edit").removeClass("mobilize-edit");
                var self = this;
                var title = $(_this).attr("title");
                var mobilizeMenuItem = $(_this).parent().parent();
                $(_this).addClass("mobilize-edit");
                //content dialog Alignment

                //content dialog type logo
                var contentDialogLogoSlogan = $("<div/>", {"class":"control-group"}).append(
                    $("<label/>", { "class":"control-label", text:self.lang['JSN_MOBILIZE_IMAGE_ALT']})).append(
                    $("<div/>", {"class":"controls" }).append(
                        $("<input/>", { "type":"text", "name":"logo_alt", "class":"logo-alt jsn-input-xlarge-fluid", "value":mobilizeMenuItem.find("a.element-edit").attr("data-state") }).bind('keyup',function () {
                            self.actionChangeLogo($(this), mobilizeMenuItem);
                        }).change(function () {
                                self.actionChangeLogo($(this), mobilizeMenuItem);
                            })
                    )
                );
                // Html content dialog type Logo
                var cotentDialogLogoSrc = $("<div/>", { "class":"control-group"}).append(
                    $("<label/>", {"class":"control-label", text:self.lang['JSN_MOBILIZE_IMAGE_FILE']})).append(
                    $("<div/>", {"class":"controls" }).append(
                        $("<div/>", { "class":"row-fluid input-append" }).append(
                            $("<input/>", { "type":"text", "name":"logo_url", "class":"logo-url jsn-input-large-fluid", "disabled":"disabled", "value":mobilizeMenuItem.find("input.data-mobilize").attr("data-id")})).append(
                            $("<button/>", { "class":"btn", "onclick":"return false;", text:"..."}).click(function () {
                                self.changeLogo();
                            })).append(
                            $("<button/>", {"class":"btn btn-icon", "onclick":"return false;"}).click(function (e) {
                                $(".mobilize-dialog input.logo-url").val("");
                                window.changeLogo("", false);

                                e.stopPropagation();
                            }).append('<i class="icon-remove"></i>'))));
                // create html dialog container
                var dialog = $("<div/>", {"class":"mobilize-dialog jsn-bootstrap" }).append(
                    $("<div/>", { "class":"popover bottom" }).css("display", "block").append(
                        $("<div/>", {"class":"arrow"})).append(
                        $("<h3/>", { "class":"popover-title", "text":title + " Settings"
                        })).append(
                        $("<div/>", { "class":"popover-content"}).append(cotentDialogLogoSrc).append(contentDialogLogoSlogan)));
                $("body").append(dialog);
                $("#jsn-alignment option").each(function () {
                    if ($(this).val() == mobilizeMenuItem.find("input.data-mobilize-alignment").val()) {
                        $(this).prop('selected', true);
                    }
                });
                var elmStyle = self.getBoxStyle($(dialog)),
                    parentStyle = self.getBoxStyle($(_this)),
                    parentStyleImg = self.getBoxStyle($("#jsn-logo img")),
                    position = {};
                position.left = parentStyle.offset.left - elmStyle.outerWidth / 2 + parentStyle.outerWidth / 2;
                if (parentStyleImg.height > parentStyle.height) {
                    position.top = parentStyle.offset.top + elmStyle.outerHeight + parentStyleImg.outerHeight / 2;
                } else {
                    position.top = parentStyle.offset.top + elmStyle.outerHeight + parentStyle.outerHeight;
                }
                dialog.css(position).click(function (e) {
                    e.stopPropagation();
                });
            },
            createPopupDialogMenuIcon:function (_this) {
                $(".mobilize-dialog").remove();
                $(".mobilize-edit").removeClass("mobilize-edit");
                var self = this;
                var dataValue = $(_this).attr("data-value");
                var dataType = $(_this).attr("data-type");
                var dataState = $(_this).attr("data-state");
                var contentDialogCheckEnable;
                var contentDialog = "";
                var title = $(_this).attr("title");
                var mobilizeMenuItem = $(_this).parent();
                $(_this).addClass("mobilize-edit");
                var filterModule = "";
                if (dataType == "mobilize-login") {
                    filterModule = "mod_login";
                }
                if (dataType == "mobilize-search") {
                    filterModule = "mod_search";
                }
                // content dialog type select module
                if (dataType != "mobilize-switcher") {
                    var mobilizeSelect = "JSN_MOBILIZE_SELECT_MODULE",
                        menuTitle = "";
                    if (dataType == "mobilize-menu") {
                        $.each(self.listMenu, function (i, item) {
                            if (item.id == dataValue) {
                                menuTitle = item.title;
                            }
                        });
                        mobilizeSelect = "JSN_MOBILIZE_SELECT_MENU";
                    } else {
                        menuTitle = self.listModule['getById'][dataValue];
                    }
                    contentDialog = $("<div/>", { "class":"control-group"}).append(
                        $("<label/>", { "class":"control-label", "text":self.lang[mobilizeSelect]})).append(
                        $("<div/>", {"class":"controls"}).append(
                            $("<div/>", {"class":"row-fluid input-append"}).append(
                                $("<input/>", { "class":"menu-title jsn-input-large-fluid", "disabled":"disabled", "type":"text", "value":menuTitle})
                            ).append(
                                $("<button/>", { "class":"btn", "onclick":"return false;", text:"..."}).click(function () {
                                    if (dataType == "mobilize-menu") {
                                        self.dialogChangeMenus();
                                    } else {
                                        self.dialogChangeModule(filterModule, 'changeModuleMenuIcon');
                                    }
                                })
                            ).append(
                                $("<button/>", { "class":"btn btn-icon", "onclick":"return false;"}).click(function (e) {
                                    var menu = mobilizeMenuItem.find("a.link-menu-mobilize.mobilize-edit");
                                    $(menu).attr("data-value", "");
                                    $(menu).next("input").attr({"data-id":"", "value":""});
                                    $(menu).trigger("click");
                                    e.stopPropagation();
                                }).append('<i class="icon-remove"></i>')
                            )
                        )
                    )
                } else {
                    contentDialog = $("<div/>", {"class":"control-group" }).append(
                        $("<label/>", {  "class":"control-label", "text":self.lang['JSN_MOBILIZE_SWITCHER_TITLE'] })).append(
                        $("<div/>", { "class":"controls" }).append(
                            $("<input/>", { "class":"switcher-title jsn-input-xlarge-fluid", "type":"text", "value":mobilizeMenuItem.find("button.btn-switcher").attr("data-value") }).bind('keyup',function () {
                                self.changeSwitcherSettings($(this).val());
                            }).change(function () {
                                    self.changeSwitcherSettings($(this).val());
                                })
                        )
                    );
                    var labelEnable = "JSN_MOBILIZE_ENABLE_" + dataType.toUpperCase().replace("-", "_") + "_LINK";
                    contentDialogCheckEnable = $("<div/>", {
                        "class":"control-group"
                    }).append(
                        $("<label/>", {
                            "class":"control-label",
                            text:self.lang[labelEnable]
                        })).append(
                        $("<div/>", {
                            "class":"controls"
                        }).append(
                            $("<label/>", {
                                "class":"radio inline"
                            }).append(
                                $("<input/>", {
                                    "type":"radio",
                                    "name":$(_this).attr("data-type"),
                                    "value":"0",
                                    "class":"jsn-check-enable"
                                })).append(self.lang["JSN_MOBILIZE_NO"])).append(
                            $("<label/>", {
                                "class":"radio inline"
                            }).append(
                                $("<input/>", {"type":"radio", "name":$(_this).attr("data-type"), "value":"1", "class":"jsn-check-enable"})).append(self.lang["JSN_MOBILIZE_YES"])
                        )
                    );
                    dataState = dataState ? dataState : 0;
                    $(contentDialogCheckEnable).find("input.jsn-check-enable").each(function () {
                        if ($(this).val() == dataState) {
                            $(this).prop("checked", true);
                        } else {
                            $(this).prop("checked", false);
                        }
                    });
                    $(contentDialogCheckEnable).find("input.jsn-check-enable").change(function () {
                        var dataInput = {};
                        dataInput[ mobilizeMenuItem.find("button.btn-switcher").attr("data-value")] = $(this).val();
                        mobilizeMenuItem.find("input.data-mobilize").val($.toJSON(dataInput));
                        mobilizeMenuItem.find("button.btn-switcher").attr("data-state", $(this).val());
                    });
                }
                // create html dialog container
                var dialog = $("<div/>", {"class":"mobilize-dialog jsn-bootstrap" }).append(
                    $("<div/>", {"class":"popover bottom"}).css("display", "block").append(
                        $("<div/>", { "class":"arrow" })).append(
                        $("<h3/>", {"class":"popover-title", "text":title + " Settings"})).append(
                        $("<div/>", { "class":"popover-content"}).append(contentDialogCheckEnable).append(contentDialog)));
                $("body").append(dialog);

                var elmStyle = self.getBoxStyle($(dialog)),
                    elmStylePopover = self.getBoxStyle($(dialog).find(".popover")),
                    parentStyle = self.getBoxStyle($(_this)),
                    position = {};

                if (dataType != "mobilize-switcher") {
                    if (parentStyle.offset.left > elmStyle.outerWidth / 2 + 50 && ($(window).width() - parentStyle.offset.left) > elmStyle.outerWidth / 2 + 50) {
                        position.left = parentStyle.offset.left - elmStyle.outerWidth / 2 + parentStyle.outerWidth / 2;
                        position.top = parentStyle.offset.top + parentStyle.outerHeight;
                    } else if (($(window).width() - parentStyle.offset.left) < elmStyle.outerWidth) {
                        position.left = parentStyle.offset.left - elmStyle.outerWidth + parentStyle.outerWidth;
                        position.top = parentStyle.offset.top + parentStyle.outerHeight;
                        $(dialog).find(".arrow").css("left", elmStyle.outerWidth - (parentStyle.outerWidth) / 2);
                    } else {
                        position.left = parentStyle.offset.left - parentStyle.outerWidth / 2;
                        position.top = parentStyle.offset.top + parentStyle.outerHeight;
                        $(dialog).find(".arrow").css("left", parentStyle.outerWidth);
                    }
                } else {
                    position.left = parentStyle.offset.left - elmStyle.outerWidth / 2 + parentStyle.outerWidth / 2;
                    position.top = parentStyle.offset.top - elmStylePopover.height - parentStyle.height / 2;
                    $(dialog).find(".popover").attr("class", "popover top");
                }
                dialog.css(position).click(function (e) {
                    e.stopPropagation();
                });
            },
            registerEvent:function () {
                var self = this;
                $(".jsn-element-container div.jsn-element .element-edit").click(function (e) {
                    $(".ui-state-edit").removeClass("ui-state-edit");
                    var item = $(this).parent().parent();
                    $(item).addClass("ui-state-edit");
                    self.editItem(item);
                    e.stopPropagation();
                });
                $(".jsn-element-container div.jsn-element .element-delete").click(function () {
                    $(this).parent().parent().remove();
                    return false;
                });
                $(".jsn-element-container").sortable({
                    connectWith:".jsn-element-container",
                    placeholder:'ui-state-highlight',
                    forcePlaceholderSize:true,
                    update:function (event, ui) {
                        if (ui.sender) {
                            var check = true,
                                active = "";
                            $(ui.item).attr("action", "move");
                            $(this).find("div.jsn-element").each(function () {
                                if ($(this).attr("data-value") == $(ui.item).attr("data-value") && $(this).attr("data-type") == $(ui.item).attr("data-type") && $(this).attr("action") != "move") {
                                    check = false;
                                    active = $(this);
                                }
                            });
                            $(ui.item).removeAttr("action");
                            if (check) {
                                var blockId = $(this).parent().attr("id");
                                var moduleStyle = $(this).parent().attr("data-module-style");
                                var moduleTitleStyle = $(this).parent().attr("data-title-style");
                                $(this).find(".jsn-element input.data-block-mobilize").each(function () {
                                    $(this).attr("name", "jsnmobilize[" + blockId + "][]");
                                });
                                $(this).find(".jsn-element").removeAttr("style");
                                $(this).find(".jsn-element").attr("style", moduleStyle);
                                $(this).find(".jsn-element .jsn-element-content").removeAttr("style");
                                $(this).find(".jsn-element .jsn-element-content").attr("style", moduleTitleStyle);

                            } else {
                                $(active).effect("highlight", {}, 3000);
                                $(ui.item).effect("highlight", {}, 3000);
                                $(ui.sender).append($(ui.item));
                                return false;
                            }
                        }
                    }
                }).disableSelection();
            },
            editItem:function (_this) {
                var self = this;
                $(".mobilize-list-edit").removeClass("mobilize-list-edit");
                $(".container-fluid .mobilize-update-jsn-element").removeClass("mobilize-update-jsn-element");
                $(_this).parent().parent().addClass("mobilize-list-edit");
                $(_this).addClass("mobilize-update-jsn-element");
                if ($(_this).attr("data-type") == "position") {
                    self.actionAddPosition();
                }
                if ($(_this).attr("data-type") == "module") {
                    self.actionAddModule('update');
                }
                updateItem = true;
            },
            //Save position
            saveItem:function (options) {
                var check = true;
                var self = this;
                var idContentAdd = $(".container-fluid .mobilize-list-edit").attr("id");
                var exIdContentAdd = idContentAdd.split('-');
                $(".mobilize-list-edit .jsn-element-container div.jsn-element").each(function () {
                    if ($(this).attr("data-value") == options.value && $(this).attr("data-type") == options.type) {
                        $(this).effect("highlight", {}, 3000);
                        check = false;
                    }
                });
                if (check) {
                    var name = $(".mobilize-list-edit").attr("id");
                    var idContetnBlock = "";
                    self.itemContent(options, name);
                    this.registerEvent();
                }
            },
            itemContent:function (options, name) {
                var self = this;
                var dataInput = {};
                dataInput[options.value] = options.type;
                var moduleStyle = $("#" + name).attr("data-module-style");
                var moduleTitleStyle = $("#" + name).attr("data-title-style");
                var contentItem = $("<div/>", {"class":"jsn-element ui-state-default jsn-iconbar-trigger ui-state-edit", "style":moduleStyle, "data-value":options.value, "data-type":options.type
                }).append(
                    $("<div/>", {"class":"jsn-element-content", style:moduleTitleStyle}).append(
                        $("<span/>", {"class":"type-element", "text":options.label + ": "})
                    ).append(
                        $("<span/>", {"class":"name-element", "text":options.name})
                    ).append(
                        $("<input/>", {"type":"hidden", "class":"data-block-mobilize", "name":"jsnmobilize[" + name + "][]", "value":$.toJSON(dataInput)})
                    )
                ).append(
                    $("<div/>", { "class":"jsn-iconbar"}).append(
                        $("<a/>", {"class":"element-edit", "href":"javascript:void(0)"}).click(function () {
                            self.editItem($(this).parent());
                        }).append('<i class="icon-pencil"></i>')).append(" ").append(
                        $("<a/>", {"class":"element-delete", "href":"javascript:void(0)"
                        }).append('<i class="icon-trash"></i>')));
                if (updateItem) {
                    $("#" + name + ".mobilize-list-edit .jsn-element-container .jsn-element.mobilize-update-jsn-element").before(contentItem).remove();
                } else {
                    $("#" + name + ".mobilize-list-edit .jsn-element-container").append(contentItem);
                }
            },
            //Change logo
            changeLogo:function () {
                var mobilizeLogo = $(".mobilize-dialog").parent();
                var value = mobilizeLogo.find("input.data-mobilize").attr("data-id");
                var element = mobilizeLogo.find("a.element-edit").attr("data-type");
                var buttons = {};
                buttons[this.lang['JSN_MOBILIZE_CLOSE']] = $.proxy(function () {
                    this.modalchangeLogo.close();
                }, this);
                this.modalchangeLogo = new JSNModal({
                    url:this.pathRoot + "plugins/system/jsnframework/libraries/joomlashine/choosers/media/index.php?component=com_mobilize&root=&current=" + value + "&element=" + element + "&handler=changeLogo",
                    title:this.lang['JSN_MOBILIZE_SELECT_LOGO'],
                    buttons:buttons,
                    height:750,
                    width:850,
                    loaded:function (modalchangeLogo) {
                        modalchangeLogo.options.loaded = null;
                        modalchangeLogo.iframe[0].contentWindow.location.reload();
                    }
                });
                this.modalchangeLogo.show();
            },
            // Create preview modal window
            createPreview:function () {
                // Save all parameters to cookie
                var self = this;
                var jsnmobilize = {}, profileStyle = {},
                    parseValue = function (value) {
                        return (value.substr(0, 1) == '{' && value.substr(-1) == '}') ? $.evalJSON(value) : value;
                    };
                $("#design input").each(function () {
                    var i = $(this).attr("name");
                    if (i.indexOf("jsnmobilize") > -1) {
                        var k, v, tmp;
                        k = i.replace('jsnmobilize', '').replace(/(\[|\])/g, '');
                        if (document.adminForm[i].length) {
                            v = {};
                            for (var i2 = 0; i2 < document.adminForm[i].length; i2++) {
                                tmp = parseValue(document.adminForm[i][i2].value);
                                for (var i3 in tmp) {
                                    v[i3] = tmp[i3];
                                }
                            }
                        } else {
                            v = parseValue(document.adminForm[i].value);
                        }
                        jsnmobilize[k] = v;
                    }
                });
                jsnmobilize['logo-alignment'] = $("input.data-mobilize-alignment").val();
                $("input.jsn-input-style").each(function () {
                    var key = $(this).attr("id");
                    key = key.replace("input_style_", "");
                    if ($(this).val()) {
                        profileStyle[key] = $.evalJSON($(this).val());
                    }
                });
                jsnmobilize['mobilize-profile-style'] = profileStyle;
                $.ajax({
                    type:"POST",
                    async:true,
                    url:"index.php?option=com_mobilize&view=profiles&task=profiles.saveSessionStyle&tmpl=component",
                    data:{
                        mobilize:$.toJSON(jsnmobilize)
                    },
                    success:function () {
                        var height = $(window).height();
                        var buttons = {};
                        buttons[self.lang['JSN_MOBILIZE_CLOSE']] = $.proxy(function () {
                            self.closeModalBox();
                        }, this);
                        var url = self.params['mobilizeLink'] + (self.params['mobilizeLink'].indexOf('?') > -1 ? '&' : '?') + '_preview=1';
                        var createIframe = $("<iframe/>", {
                            "class":"jsn-mobilize-preview hide",
                            "src":url,
                            height:height * (90 / 100),
                            width:'100%'
                        }).load(function () {
                                // $(".jsn-bgloading").remove();
                                self.changeViewMobilize();
                                $(createIframe).removeClass("hide");
                                $("#mobilize-design #jsn-mobilize").hide();
                                $(".jsn-mobilize-preview").show();
                                $(".jsn-modal-overlay,.jsn-modal-indicator").remove();
                            });
                        /*
                         $("#mobilize-design").append($("<div/>", {
                         "class":"jsn-bgloading"
                         }).append($("<i/>", {
                         "class":"jsn-icon32 jsn-icon-loading"
                         })));*/
                        $("#mobilize-design .jsn-mobilize-preview").remove();
                        //  $("#mobilize-design #jsn-mobilize").hide();
                        // $("#mobilize-design").append(createIframe);
                        $("#mobilize-design").append(createIframe);
                        $(".jsn-mobilize-preview").hide();
                    }
                });
            },
            //Close modal window
            closeModalBox:function () {
                var self = this;
                self.modalMobilize.close();
                $(".ui-dialog").remove();
                $(".jsn-modal").remove();
            },
            // Action add postion
            actionAddPosition:function () {
                var height = $(window).height();
                var width = $(window).width();
                var buttons = {};
                var self = this;
                buttons[this.lang['JSN_MOBILIZE_CLOSE']] = $.proxy(function () {
                    self.closeModalBox();
                }, this);
                this.modalMobilize = new JSNModal({
                    url:'index.php?option=com_mobilize&view=positions&tmpl=component&function=jSelectPosition&filter_template=' + this.defaultTemplate + '&filter_type=template&filter_state=1',
                    title:this.lang['JSN_MOBILIZE_SELECT_POSITION'],
                    buttons:buttons,
                    height:height * (95 / 100),
                    width:width * (95 / 100),
                    scrollable:true
                });
                this.modalMobilize.show();
            },
            dialogChangeMenus:function () {
                var self = this;
                var height = $(window).height();
                var width = $(window).width();
                var buttons = {};
                buttons[this.lang['JSN_MOBILIZE_CANCEL']] = $.proxy(function () {
                    $(".container-fluid .mobilize-list-edit").removeClass("mobilize-list-edit");
                    self.closeModalBox();
                }, this);
                this.modalMobilize = new JSNModal({
                    url:"index.php?option=com_mobilize&view=menus&layout=menus&tmpl=component&jsnaction=update",
                    title:this.lang['JSN_MOBILIZE_SELECT_MENU'],
                    buttons:buttons,
                    height:height * (95 / 100),
                    width:width * (95 / 100),
                    scrollable:true
                });
                this.modalMobilize.show();
            },
            dialogChangeModule:function (filter, getfunction) {
                var self = this;
                var height = $(window).height();
                var width = $(window).width();
                var buttons = {};
                buttons[this.lang['JSN_MOBILIZE_CANCEL']] = $.proxy(function () {
                    $(".container-fluid .mobilize-list-edit").removeClass("mobilize-list-edit");
                    self.closeModalBox();
                }, this);
                this.modalMobilize = new JSNModal({
                    url:'index.php?option=com_mobilize&view=modules&layout=default&tmpl=component&function=' + getfunction + '&filter_client_id=0&filter_state=1&filter_module=' + filter + "&jsnaction=update&modulesAction=menu",
                    title:this.lang['JSN_MOBILIZE_SELECT_MODULE'],
                    buttons:buttons,
                    height:height * (95 / 100),
                    width:width * (95 / 100),
                    scrollable:true
                });
                this.modalMobilize.show();
            },
            // Action add module
            actionAddModule:function (action) {
                var self = this;
                var height = $(window).height();
                var width = $(window).width();
                var buttons = {};
                if (action != "update") {
                    buttons[this.lang['JSN_MOBILIZE_SELECT']] = $.proxy(function () {
                        var moduleList = $.getModuleList(),
                            blackList = [];
                        $(".mobilize-list-edit .jsn-element-container .jsn-element").each(function () {
                            if ($(this).attr("data-type") == "module") {
                                var check = true,
                                    _this = this;
                                $.each(moduleList, function (i, val) {
                                    if (val.value == $(_this).attr("data-value")) {
                                        check = false;
                                        blackList.push(val.value);
                                    }
                                });
                                if (check) {
                                    $(this).remove();
                                }
                            }
                        });
                        $.each(moduleList, function (i, val) {
                            if ($.inArray(val.value, blackList) < 0) {
                                val.type = "module";
                                val.label = self.lang["JSN_MOBILIZE_TYPE_MODULE"];
                                self.saveItem(val);
                            }
                        });
                        $(".mobilize-dialog").remove();
                        self.closeModalBox();
                    }, this);
                }
                buttons[this.lang['JSN_MOBILIZE_CLOSE']] = $.proxy(function () {
                    self.closeModalBox();
                }, this);
                this.modalMobilize = new JSNModal({
                    url:'index.php?option=com_mobilize&view=modules&layout=default&tmpl=component&function=jSelectModules&filter_client_id=0&filter_state=1&filter_module=&jsnaction=' + action,
                    title:this.lang['JSN_MOBILIZE_SELECT_MODULE'],
                    buttons:buttons,
                    height:height * (95 / 100),
                    width:width * (95 / 100),
                    scrollable:true
                });
                this.modalMobilize.show();
            },
            getBoxStyle:function (element) {
                var style = {
                    width:element.width(),
                    height:element.height(),
                    outerHeight:element.outerHeight(),
                    outerWidth:element.outerWidth(),
                    offset:element.offset(),
                    margin:{
                        left:parseInt(element.css('margin-left')),
                        right:parseInt(element.css('margin-right')),
                        top:parseInt(element.css('margin-top')),
                        bottom:parseInt(element.css('margin-bottom'))
                    },
                    padding:{
                        left:parseInt(element.css('padding-left')),
                        right:parseInt(element.css('padding-right')),
                        top:parseInt(element.css('padding-top')),
                        bottom:parseInt(element.css('padding-bottom'))
                    }
                };
                return style;
            }
        }
        return JSNMobilizeProfileView;
    })