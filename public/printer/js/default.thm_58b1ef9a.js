window.app=window.app||{};
window.skins=window.skins||{};
                function __extends(d, b) {
                    for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p];
                        function __() {
                            this.constructor = d;
                        }
                    __.prototype = b.prototype;
                    d.prototype = new __();
                };
                window.generateEUI = {};
                generateEUI.paths = {};
                generateEUI.styles = {"cd_label":{"size":14,"textColor":"0x4C4E51"}};
                generateEUI.skins = {"eui.Button":"resource/eui_skins/ButtonSkin.exml","eui.CheckBox":"resource/eui_skins/CheckBoxSkin.exml","eui.HScrollBar":"resource/eui_skins/HScrollBarSkin.exml","eui.HSlider":"resource/eui_skins/HSliderSkin.exml","eui.Panel":"resource/eui_skins/PanelSkin.exml","eui.TextInput":"resource/eui_skins/TextInputSkin.exml","eui.ProgressBar":"resource/eui_skins/ProgressBarSkin.exml","eui.RadioButton":"resource/eui_skins/RadioButtonSkin.exml","eui.Scroller":"resource/eui_skins/ScrollerSkin.exml","eui.ToggleSwitch":"resource/eui_skins/ToggleSwitchSkin.exml","eui.VScrollBar":"resource/eui_skins/VScrollBarSkin.exml","eui.VSlider":"resource/eui_skins/VSliderSkin.exml","eui.ItemRenderer":"resource/eui_skins/ItemRendererSkin.exml","cdcommon.framework.components.DropDownList":"resource/eui_skins/DropDownListSkin.exml","cdcommon.framework.components.AlertPic":"resource/eui_skins/app/AlertPicSkin.exml","cdcommon.framework.components.AlertTip":"resource/eui_skins/app/AlertTipSkin.exml","cdcommon.framework.components.CircleLabel":"resource/eui_skins/app/CircleLabelSkin.exml","cdcommon.editor.components.FaceThickness":"resource/eui_skins/app/FaceThicknessSkin.exml","cdcommon.editor.components.PageList":"resource/eui_skins/app/PageListSkin.exml","cdcommon.editor.components.BackgroundElement":"resource/eui_skins/app/BackgroundElementSkin.exml","cdcommon.editor.components.DecorationElement":"resource/eui_skins/app/DecorationElementSkin.exml","cdcommon.editor.components.PhotoElement":"resource/eui_skins/app/PhotoElementSkin.exml","cdcommon.editor.components.SpecialElement":"resource/eui_skins/app/SpecialElementSkin.exml","cdcommon.editor.components.TextElement":"resource/eui_skins/app/TextElementSkin.exml","cdcommon.editor.components.CalendarElement":"resource/eui_skins/app/CalendarElementSkin.exml","components.LocationSelectionBar":"resource/eui_skins/app/LocationSelectionBarSkin.exml","components.PhotoItemRenderer":"resource/eui_skins/app/PhotoItemRendererSkin.exml","components.PhotoManagerPanel":"resource/eui_skins/app/PhotoManagerPanelSkin.exml","components.PageItemContainer":"resource/eui_skins/app/PageItemContainerSkin.exml","views.PageEditorView":"resource/eui_skins/app/PageEditorViewSkin.exml","views.SaveWorksConfirmPopup":"resource/eui_skins/app/SaveWorksConfirmPopupSkin.exml","views.AgentSaveWorkResultPopup":"resource/eui_skins/app/AgentSaveWorkResultPopupSkin.exml","views.AgentSubmitWorkResultView":"resource/eui_skins/app/AgentSubmitWorkResultViewSkin.exml"};generateEUI.paths['resource/eui_skins/app/TextAreaSkin.exml'] = window.app.TextAreaSkin = (function (_super) {
	__extends(TextAreaSkin, _super);
	function TextAreaSkin() {
		_super.call(this);
		this.skinParts = ["textDisplay","promptDisplay"];
		
		this.minHeight = 30;
		this.minWidth = 200;
		this.elementsContent = [this._Rect1_i(),this.textDisplay_i()];
		this.promptDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("disabled",
				[
				])
			,
			new eui.State ("normalWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
			,
			new eui.State ("disabledWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
		];
	}
	var _proto = TextAreaSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0xffffff;
		t.percentHeight = 100;
		t.strokeColor = 0xBCBCBC;
		t.strokeWeight = 1.5;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.percentWidth = 100;
		return t;
	};
	_proto.textDisplay_i = function () {
		var t = new eui.EditableText();
		this.textDisplay = t;
		t.bottom = "0";
		t.left = "10";
		t.multiline = true;
		t.right = "10";
		t.size = 12;
		t.textColor = 0x000000;
		t.top = "10";
		t.verticalAlign = "top";
		return t;
	};
	_proto.promptDisplay_i = function () {
		var t = new eui.Label();
		this.promptDisplay = t;
		t.style = "cd_label";
		t.bottom = 0;
		t.left = 10;
		t.multiline = true;
		t.right = 10;
		t.size = 12;
		t.textColor = 0xA2A2A2;
		t.top = 10;
		t.touchEnabled = false;
		return t;
	};
	return TextAreaSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/OKButtonSkin.exml'] = window.app.OKButtonSkin = (function (_super) {
	__extends(OKButtonSkin, _super);
	function OKButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Rect1_i(),this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Rect1","fillAlpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
				])
		];
	}
	var _proto = OKButtonSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		this._Rect1 = t;
		t.bottom = 0;
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0xFC344D;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 10;
		t.left = 15;
		t.right = 15;
		t.top = 10;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 5;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.verticalAlign = "middle";
		return t;
	};
	return OKButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CancelButtonSkin.exml'] = window.app.CancelButtonSkin = (function (_super) {
	__extends(CancelButtonSkin, _super);
	function CancelButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Rect1_i(),this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Rect1","fillAlpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
				])
		];
	}
	var _proto = CancelButtonSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		this._Rect1 = t;
		t.bottom = 0;
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0xc4c4c4;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 10;
		t.left = 15;
		t.right = 15;
		t.top = 10;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 5;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.verticalAlign = "middle";
		return t;
	};
	return CancelButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/AgentSaveWorkResultPopupSkin.exml'] = window.app.AgentSaveWorkResultPopupSkin = (function (_super) {
	__extends(AgentSaveWorkResultPopupSkin, _super);
	var AgentSaveWorkResultPopupSkin$Skin1 = 	(function (_super) {
		__extends(AgentSaveWorkResultPopupSkin$Skin1, _super);
		function AgentSaveWorkResultPopupSkin$Skin1() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = AgentSaveWorkResultPopupSkin$Skin1.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.source = "btn_win_close_png";
			return t;
		};
		return AgentSaveWorkResultPopupSkin$Skin1;
	})(eui.Skin);

	function AgentSaveWorkResultPopupSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","moveArea","titleDisplay","closeButton","topGroup","workUrlInput","copyButton","saveWorkUrlButton","addToFavoriteButton","cancelButton","contentGroup"];
		
		this.elementsContent = [this.backgroundRect_i(),this.topGroup_i(),this.contentGroup_i()];
	}
	var _proto = AgentSaveWorkResultPopupSkin.prototype;

	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 55;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect1_i(),this.moveArea_i(),this.titleDisplay_i(),this.closeButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 100;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.style = "cd_label";
		t.left = 30;
		t.size = 20;
		t.textColor = 0x000000;
		t.top = 20;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.right = 20;
		t.top = 20;
		t.skinName = AgentSaveWorkResultPopupSkin$Skin1;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.top = 56;
		t.width = 480;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Label1_i(),this._Group1_i(),this._Group2_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 20;
		t.horizontalAlign = "center";
		t.paddingBottom = 20;
		t.paddingLeft = 30;
		t.paddingRight = 30;
		t.paddingTop = 20;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.lineSpacing = 5;
		t.multiline = true;
		t.size = 12;
		t.text = "作品保存成功！如果您想要继续制作，您可以复制并保存作品地址，方便下次编辑使用哦。";
		t.textColor = 0xA2A2A2;
		t.percentWidth = 100;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Label2_i(),this.workUrlInput_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "作品地址";
		return t;
	};
	_proto.workUrlInput_i = function () {
		var t = new eui.TextInput();
		this.workUrlInput = t;
		t.height = 80;
		t.skinName = "app.TextAreaSkin";
		t.percentWidth = 100;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		t.elementsContent = [this.copyButton_i(),this.saveWorkUrlButton_i(),this.addToFavoriteButton_i(),this.cancelButton_i()];
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		t.horizontalAlign = "right";
		return t;
	};
	_proto.copyButton_i = function () {
		var t = new eui.Button();
		this.copyButton = t;
		t.height = 30;
		t.label = "复制";
		t.skinName = "app.OKButtonSkin";
		t.width = 60;
		return t;
	};
	_proto.saveWorkUrlButton_i = function () {
		var t = new eui.Button();
		this.saveWorkUrlButton = t;
		t.height = 30;
		t.label = "保存链接";
		t.skinName = "app.OKButtonSkin";
		t.width = 90;
		return t;
	};
	_proto.addToFavoriteButton_i = function () {
		var t = new eui.Button();
		this.addToFavoriteButton = t;
		t.height = 30;
		t.label = "加入收藏夹";
		t.skinName = "app.OKButtonSkin";
		t.width = 105;
		return t;
	};
	_proto.cancelButton_i = function () {
		var t = new eui.Button();
		this.cancelButton = t;
		t.height = 30;
		t.label = "关闭";
		t.skinName = "app.CancelButtonSkin";
		t.width = 80;
		return t;
	};
	return AgentSaveWorkResultPopupSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/AgentSubmitWorkResultViewSkin.exml'] = window.app.AgentSubmitWorkResultViewSkin = (function (_super) {
	__extends(AgentSubmitWorkResultViewSkin, _super);
	var AgentSubmitWorkResultViewSkin$Skin2 = 	(function (_super) {
		__extends(AgentSubmitWorkResultViewSkin$Skin2, _super);
		function AgentSubmitWorkResultViewSkin$Skin2() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group2","alpha",0.45)
					])
			];
		}
		var _proto = AgentSubmitWorkResultViewSkin$Skin2.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			this._Group2 = t;
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return AgentSubmitWorkResultViewSkin$Skin2;
	})(eui.Skin);

	function AgentSubmitWorkResultViewSkin() {
		_super.call(this);
		this.skinParts = ["copyWorksNameButton","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
	}
	var _proto = AgentSubmitWorkResultViewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this._Rect1_i(),this._Group2_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Image1_i(),this._Group1_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 20;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.height = 90;
		t.smoothing = true;
		t.source = "alert_complete_png";
		t.touchEnabled = false;
		t.width = 90;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Label1_i(),this._Label2_i(),this.copyWorksNameButton_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 10;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.bold = true;
		t.size = 25;
		t.text = "您的作品已经提交成功！";
		t.textColor = 0x00000;
		t.touchEnabled = false;
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.size = 14;
		t.text = "订单支付后，生产需要4-7个工作日！（不含周末节假日和快递时间）";
		t.textColor = 0x00000;
		return t;
	};
	_proto.copyWorksNameButton_i = function () {
		var t = new eui.Button();
		this.copyWorksNameButton = t;
		t.height = 30;
		t.label = "复制作品名";
		t.width = 100;
		t.skinName = AgentSubmitWorkResultViewSkin$Skin2;
		return t;
	};
	return AgentSubmitWorkResultViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/AlertPicConfirmSkin.exml'] = window.app.AlertPicConfirmSkin = (function (_super) {
	__extends(AlertPicConfirmSkin, _super);
	function AlertPicConfirmSkin() {
		_super.call(this);
		this.skinParts = ["titleDisplay","moveArea","imageComp","contentComp","buttonGroup","mainContent","contentGroup","contentGroups"];
		
		this.width = 300;
		this.elementsContent = [this.contentGroups_i()];
	}
	var _proto = AlertPicConfirmSkin.prototype;

	_proto.contentGroups_i = function () {
		var t = new eui.Group();
		this.contentGroups = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Rect1_i(),this.contentGroup_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.moveArea_i(),this.mainContent_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 40;
		t.percentWidth = 100;
		t.elementsContent = [this.titleDisplay_i()];
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.style = "cd_label";
		t.left = 15;
		t.size = 16;
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		t.wordWrap = false;
		return t;
	};
	_proto.mainContent_i = function () {
		var t = new eui.Group();
		this.mainContent = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this._Group2_i(),this.buttonGroup_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 20;
		t.horizontalAlign = "center";
		t.paddingBottom = 15;
		t.paddingLeft = 20;
		t.paddingRight = 20;
		t.paddingTop = 15;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.imageComp_i(),this._Group1_i(),this.contentComp_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.horizontalAlign = "center";
		t.verticalAlign = "top";
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.smoothing = true;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 1;
		t.width = 10;
		return t;
	};
	_proto.contentComp_i = function () {
		var t = new eui.Label();
		this.contentComp = t;
		t.style = "cd_label";
		t.percentHeight = 100;
		t.lineSpacing = 7;
		t.multiline = true;
		t.size = 14;
		t.percentWidth = 100;
		return t;
	};
	_proto.buttonGroup_i = function () {
		var t = new eui.Group();
		this.buttonGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 20;
		t.horizontalAlign = "center";
		return t;
	};
	return AlertPicConfirmSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/AlertPicSkin.exml'] = window.app.AlertPicSkin = (function (_super) {
	__extends(AlertPicSkin, _super);
	function AlertPicSkin() {
		_super.call(this);
		this.skinParts = ["titleDisplay","moveArea","imageComp","contentComp","okButton","mainContent","contentGroup","contentGroups"];
		
		this.width = 300;
		this.elementsContent = [this.contentGroups_i()];
	}
	var _proto = AlertPicSkin.prototype;

	_proto.contentGroups_i = function () {
		var t = new eui.Group();
		this.contentGroups = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Rect1_i(),this.contentGroup_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.moveArea_i(),this.mainContent_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 40;
		t.percentWidth = 100;
		t.elementsContent = [this.titleDisplay_i()];
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.left = 15;
		t.size = 16;
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		t.wordWrap = false;
		return t;
	};
	_proto.mainContent_i = function () {
		var t = new eui.Group();
		this.mainContent = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this._Group2_i(),this.okButton_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 20;
		t.horizontalAlign = "center";
		t.paddingBottom = 15;
		t.paddingLeft = 20;
		t.paddingRight = 20;
		t.paddingTop = 15;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.imageComp_i(),this._Group1_i(),this.contentComp_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.horizontalAlign = "center";
		t.verticalAlign = "top";
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.smoothing = true;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 1;
		t.width = 10;
		return t;
	};
	_proto.contentComp_i = function () {
		var t = new eui.Label();
		this.contentComp = t;
		t.style = "cd_label";
		t.percentHeight = 100;
		t.multiline = true;
		t.size = 14;
		t.percentWidth = 100;
		return t;
	};
	_proto.okButton_i = function () {
		var t = new eui.Button();
		this.okButton = t;
		return t;
	};
	return AlertPicSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/AlertTipSkin.exml'] = window.app.AlertTipSkin = (function (_super) {
	__extends(AlertTipSkin, _super);
	function AlertTipSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","textComp","contentGroups"];
		
		this.elementsContent = [this.contentGroups_i()];
	}
	var _proto = AlertTipSkin.prototype;

	_proto.contentGroups_i = function () {
		var t = new eui.Group();
		this.contentGroups = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this.backgroundRect_i(),this.textComp_i()];
		return t;
	};
	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillAlpha = 0.5;
		t.fillColor = 0x000000;
		t.left = 0;
		t.right = 0;
		t.strokeAlpha = 1;
		t.strokeColor = 0xCCCCCC;
		t.top = 0;
		return t;
	};
	_proto.textComp_i = function () {
		var t = new eui.Label();
		this.textComp = t;
		t.bottom = 20;
		t.horizontalCenter = 0;
		t.left = 30;
		t.lineSpacing = 7;
		t.multiline = true;
		t.right = 30;
		t.size = 14;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.top = 20;
		t.verticalCenter = 0;
		return t;
	};
	return AlertTipSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/BackgroundElementSkin.exml'] = window.app.BackgroundElementSkin = (function (_super) {
	__extends(BackgroundElementSkin, _super);
	function BackgroundElementSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","loadingDisplay","imageComp"];
		
		this.elementsContent = [this._Group1_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","_Group1",2,"imageComp")
				])
		];
	}
	var _proto = BackgroundElementSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.elementsContent = [this.backgroundRect_i(),this.imageComp_i()];
		return t;
	};
	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	return BackgroundElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarElementSkin.exml'] = window.app.CalendarElementSkin = (function (_super) {
	__extends(CalendarElementSkin, _super);
	function CalendarElementSkin() {
		_super.call(this);
		this.skinParts = ["loadingDisplay","monthNumLabelDisplay","yearLabelDisplay","monthNameLabelDisplay","daysHeaderDataGroup","daysListDataGroup"];
		
		this.elementsContent = [this._Group3_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
					new eui.SetProperty("_Group3","visible",true)
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","",2,"_Group3")
				])
		];
	}
	var _proto = CalendarElementSkin.prototype;

	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		this._Group3 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.visible = false;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Group2_i(),this.daysHeaderDataGroup_i(),this.daysListDataGroup_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 5;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.monthNumLabelDisplay_i(),this._Label1_i(),this._Group1_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.monthNumLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNumLabelDisplay = t;
		t.bold = true;
		t.fontFamily = "Arial";
		t.size = 90;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.fontFamily = "Arial";
		t.size = 90;
		t.text = "/";
		t.textColor = 0x000000;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this.yearLabelDisplay_i(),this.monthNameLabelDisplay_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		t.horizontalAlign = "left";
		return t;
	};
	_proto.yearLabelDisplay_i = function () {
		var t = new eui.Label();
		this.yearLabelDisplay = t;
		t.fontFamily = "Arial";
		t.size = 33;
		t.percentWidth = 100;
		return t;
	};
	_proto.monthNameLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNameLabelDisplay = t;
		t.fontFamily = "Arial";
		t.size = 33;
		t.percentWidth = 100;
		return t;
	};
	_proto.daysHeaderDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysHeaderDataGroup = t;
		t.itemRendererSkinName = app.CalendarHeaderRendererSkin;
		t.useVirtualLayout = false;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.daysListDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysListDataGroup = t;
		t.itemRendererSkinName = app.CalendarItemRendererSkin;
		t.useVirtualLayout = false;
		t.layout = this._TileLayout1_i();
		return t;
	};
	_proto._TileLayout1_i = function () {
		var t = new eui.TileLayout();
		t.horizontalGap = 0;
		t.verticalGap = 0;
		return t;
	};
	return CalendarElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarElementStyle02Skin.exml'] = window.app.CalendarElementStyle02Skin = (function (_super) {
	__extends(CalendarElementStyle02Skin, _super);
	function CalendarElementStyle02Skin() {
		_super.call(this);
		this.skinParts = ["loadingDisplay","monthNumLabelDisplay","monthNameLabelDisplay","yearLabelDisplay","daysHeaderDataGroup","daysListDataGroup"];
		
		this.elementsContent = [this._Group4_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
					new eui.SetProperty("_Group4","visible",true)
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","",2,"_Group4")
				])
		];
	}
	var _proto = CalendarElementStyle02Skin.prototype;

	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		this._Group4 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.visible = false;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Group2_i(),this._Group3_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 20;
		t.verticalAlign = "top";
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.monthNumLabelDisplay_i(),this.monthNameLabelDisplay_i(),this._Group1_i(),this.yearLabelDisplay_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		t.horizontalAlign = "center";
		t.verticalAlign = "top";
		return t;
	};
	_proto.monthNumLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNumLabelDisplay = t;
		t.bold = true;
		t.fontFamily = "Arial";
		t.size = 117;
		return t;
	};
	_proto.monthNameLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNameLabelDisplay = t;
		t.fontFamily = "Arial";
		t.size = 29;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 10;
		t.width = 1;
		return t;
	};
	_proto.yearLabelDisplay_i = function () {
		var t = new eui.Label();
		this.yearLabelDisplay = t;
		t.fontFamily = "Arial";
		t.size = 50;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this.daysHeaderDataGroup_i(),this.daysListDataGroup_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 5;
		return t;
	};
	_proto.daysHeaderDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysHeaderDataGroup = t;
		t.itemRendererSkinName = app.CalendarHeaderRendererSkin;
		t.useVirtualLayout = false;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.daysListDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysListDataGroup = t;
		t.itemRendererSkinName = app.CalendarItemRendererSkin;
		t.useVirtualLayout = false;
		t.layout = this._TileLayout1_i();
		return t;
	};
	_proto._TileLayout1_i = function () {
		var t = new eui.TileLayout();
		t.horizontalGap = 0;
		t.verticalGap = 0;
		return t;
	};
	return CalendarElementStyle02Skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarElementStyle03Skin.exml'] = window.app.CalendarElementStyle03Skin = (function (_super) {
	__extends(CalendarElementStyle03Skin, _super);
	function CalendarElementStyle03Skin() {
		_super.call(this);
		this.skinParts = ["loadingDisplay","yearLabelDisplay","monthNumLabelDisplay","monthNameLabelDisplay","daysHeaderDataGroup","daysListDataGroup"];
		
		this.elementsContent = [this._Group3_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
					new eui.SetProperty("_Group3","visible",true)
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","",2,"_Group3")
				])
		];
	}
	var _proto = CalendarElementStyle03Skin.prototype;

	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		this._Group3 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.visible = false;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Group2_i(),this.daysHeaderDataGroup_i(),this.daysListDataGroup_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.yearLabelDisplay_i(),this._Label1_i(),this.monthNumLabelDisplay_i(),this._Group1_i(),this.monthNameLabelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.yearLabelDisplay_i = function () {
		var t = new eui.Label();
		this.yearLabelDisplay = t;
		t.bold = true;
		t.fontFamily = "Arial";
		t.size = 50;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.bold = true;
		t.fontFamily = "Arial";
		t.size = 50;
		t.text = ".";
		t.textAlign = "center";
		t.textColor = 0x000000;
		return t;
	};
	_proto.monthNumLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNumLabelDisplay = t;
		t.bold = true;
		t.fontFamily = "Arial";
		t.size = 50;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 1;
		t.width = 10;
		return t;
	};
	_proto.monthNameLabelDisplay_i = function () {
		var t = new eui.Label();
		this.monthNameLabelDisplay = t;
		t.fontFamily = "Arial";
		t.size = 50;
		return t;
	};
	_proto.daysHeaderDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysHeaderDataGroup = t;
		t.itemRendererSkinName = app.CalendarHeaderRendererSkin;
		t.useVirtualLayout = false;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.daysListDataGroup_i = function () {
		var t = new eui.DataGroup();
		this.daysListDataGroup = t;
		t.itemRendererSkinName = app.CalendarItemRendererSkin;
		t.useVirtualLayout = false;
		t.layout = this._TileLayout1_i();
		return t;
	};
	_proto._TileLayout1_i = function () {
		var t = new eui.TileLayout();
		t.horizontalGap = 0;
		t.verticalGap = 0;
		return t;
	};
	return CalendarElementStyle03Skin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarHeaderRendererSkin.exml'] = window.app.CalendarHeaderRendererSkin = (function (_super) {
	__extends(CalendarHeaderRendererSkin, _super);
	function CalendarHeaderRendererSkin() {
		_super.call(this);
		this.skinParts = [];
		
		this.elementsContent = [this._Label1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
				])
			,
			new eui.State ("disabled",
				[
				])
		];
		
		eui.Binding.$bindProperties(this, ["hostComponent.data.size"],[0],this._Label1,"size");
		eui.Binding.$bindProperties(this, ["hostComponent.data.label"],[0],this._Label1,"text");
		eui.Binding.$bindProperties(this, ["hostComponent.data.color"],[0],this._Label1,"textColor");
		eui.Binding.$bindProperties(this, ["hostComponent.data.width"],[0],this._Label1,"width");
	}
	var _proto = CalendarHeaderRendererSkin.prototype;

	_proto._Label1_i = function () {
		var t = new eui.Label();
		this._Label1 = t;
		t.fontFamily = "微软雅黑";
		t.textAlign = "center";
		t.touchEnabled = false;
		t.verticalCenter = 0;
		return t;
	};
	return CalendarHeaderRendererSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarItemRendererSkin.exml'] = window.app.CalendarItemRendererSkin = (function (_super) {
	__extends(CalendarItemRendererSkin, _super);
	function CalendarItemRendererSkin() {
		_super.call(this);
		this.skinParts = [];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
				])
			,
			new eui.State ("disabled",
				[
				])
		];
		
		eui.Binding.$bindProperties(this, ["hostComponent.data.daySize"],[0],this._Label1,"size");
		eui.Binding.$bindProperties(this, ["hostComponent.data.dayName"],[0],this._Label1,"text");
		eui.Binding.$bindProperties(this, ["hostComponent.data.dayColor"],[0],this._Label1,"textColor");
		eui.Binding.$bindProperties(this, ["hostComponent.data.chineseDaySize"],[0],this._Label2,"size");
		eui.Binding.$bindProperties(this, ["hostComponent.data.chineseName"],[0],this._Label2,"text");
		eui.Binding.$bindProperties(this, ["hostComponent.data.chineseDayColor"],[0],this._Label2,"textColor");
	}
	var _proto = CalendarItemRendererSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.verticalCenter = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Label1_i(),this._Label2_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		t.horizontalAlign = "center";
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		this._Label1 = t;
		t.fontFamily = "Arial";
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		this._Label2 = t;
		t.fontFamily = "微软雅黑";
		return t;
	};
	return CalendarItemRendererSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CalendarSelectionPopupSkin.exml'] = window.app.CalendarSelectionPopupSkin = (function (_super) {
	__extends(CalendarSelectionPopupSkin, _super);
	function CalendarSelectionPopupSkin() {
		_super.call(this);
		this.skinParts = ["yearTypeRadioButtonGroup","titleDisplay","moveArea","confirmButton","showMonthRangeDisplayForOne","startYearDDList","oneYearGroup","faceYearDDList","showMonthRangeDisplayForTwo","firstMonthDDList","twoYearGroup","mainContent","contentGroup","contentGroups"];
		
		this.yearTypeRadioButtonGroup_i();
		this.elementsContent = [this.contentGroups_i()];
		this.states = [
			new eui.State ("oneYear",
				[
					new eui.SetProperty("oneYearGroup","visible",true)
				])
			,
			new eui.State ("twoYear",
				[
					new eui.SetProperty("twoYearGroup","visible",true)
				])
		];
		
		eui.Binding.$bindProperties(this, ["yearTypeRadioButtonGroup"],[0],this._RadioButton1,"group");
		eui.Binding.$bindProperties(this, ["yearTypeRadioButtonGroup"],[0],this._RadioButton2,"group");
	}
	var _proto = CalendarSelectionPopupSkin.prototype;

	_proto.yearTypeRadioButtonGroup_i = function () {
		var t = new eui.RadioButtonGroup();
		this.yearTypeRadioButtonGroup = t;
		return t;
	};
	_proto.contentGroups_i = function () {
		var t = new eui.Group();
		this.contentGroups = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Rect1_i(),this.contentGroup_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.moveArea_i(),this.mainContent_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 40;
		t.percentWidth = 100;
		t.elementsContent = [this.titleDisplay_i()];
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.left = 10;
		t.size = 18;
		t.verticalCenter = 0;
		return t;
	};
	_proto.mainContent_i = function () {
		var t = new eui.Group();
		this.mainContent = t;
		t.height = 300;
		t.width = 380;
		t.elementsContent = [this._Label1_i(),this._Group1_i(),this.confirmButton_i(),this.oneYearGroup_i(),this.twoYearGroup_i()];
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.horizontalCenter = 0;
		t.size = 16;
		t.text = "您可以选择任意连续的12个月定制台历";
		t.top = 20;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.top = 50;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._RadioButton1_i(),this._RadioButton2_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto._RadioButton1_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton1 = t;
		t.label = "单年台历";
		t.selected = true;
		t.value = "oneYear";
		return t;
	};
	_proto._RadioButton2_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton2 = t;
		t.label = "跨年台历";
		t.value = "twoYear";
		return t;
	};
	_proto.confirmButton_i = function () {
		var t = new eui.Button();
		this.confirmButton = t;
		t.bottom = 15;
		t.height = 40;
		t.horizontalCenter = 0;
		t.label = "开始制作";
		t.skinName = "app.OKButtonSkin";
		t.width = 150;
		return t;
	};
	_proto.oneYearGroup_i = function () {
		var t = new eui.Group();
		this.oneYearGroup = t;
		t.height = 140;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.visible = false;
		t.width = 310;
		t.elementsContent = [this._Rect2_i(),this.showMonthRangeDisplayForOne_i(),this._Group2_i()];
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xf2f2f2;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.showMonthRangeDisplayForOne_i = function () {
		var t = new eui.Label();
		this.showMonthRangeDisplayForOne = t;
		t.style = "cd_label";
		t.left = 105;
		t.size = 12;
		t.top = 80;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.height = 30;
		t.horizontalCenter = 0;
		t.top = 35;
		t.elementsContent = [this._Label2_i(),this.startYearDDList_i()];
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "台历年份：";
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto.startYearDDList_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.startYearDDList = t;
		t.left = 75;
		t.top = 0;
		t.width = 170;
		return t;
	};
	_proto.twoYearGroup_i = function () {
		var t = new eui.Group();
		this.twoYearGroup = t;
		t.height = 140;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.visible = false;
		t.width = 310;
		t.elementsContent = [this._Rect3_i(),this._Group3_i(),this.showMonthRangeDisplayForTwo_i(),this._Group4_i()];
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xf2f2f2;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		t.bottom = 15;
		t.height = 30;
		t.horizontalCenter = 0;
		t.elementsContent = [this._Label3_i(),this.faceYearDDList_i()];
		return t;
	};
	_proto._Label3_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "封面年份：";
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto.faceYearDDList_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.faceYearDDList = t;
		t.left = 75;
		t.top = 0;
		t.width = 170;
		return t;
	};
	_proto.showMonthRangeDisplayForTwo_i = function () {
		var t = new eui.Label();
		this.showMonthRangeDisplayForTwo = t;
		t.style = "cd_label";
		t.left = 105;
		t.size = 12;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		t.height = 30;
		t.horizontalCenter = 0;
		t.top = 15;
		t.elementsContent = [this._Label4_i(),this.firstMonthDDList_i()];
		return t;
	};
	_proto._Label4_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "起始月份：";
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto.firstMonthDDList_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.firstMonthDDList = t;
		t.left = 75;
		t.top = 0;
		t.width = 170;
		return t;
	};
	return CalendarSelectionPopupSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/ChildStageOverviewSkin.exml'] = window.app.ChildStageOverviewSkin = (function (_super) {
	__extends(ChildStageOverviewSkin, _super);
	function ChildStageOverviewSkin() {
		_super.call(this);
		this.skinParts = ["bottomLayerContainer","backgroundColorContainer","backgroundGroup","stageGroup","tipLayerContainer","bleedTipRect","pageBorderRect","designLayerContainer","topLayerContainer"];
		
		this.elementsContent = [this.bottomLayerContainer_i(),this.designLayerContainer_i(),this.topLayerContainer_i()];
	}
	var _proto = ChildStageOverviewSkin.prototype;

	_proto.bottomLayerContainer_i = function () {
		var t = new eui.Group();
		this.bottomLayerContainer = t;
		return t;
	};
	_proto.designLayerContainer_i = function () {
		var t = new eui.Group();
		this.designLayerContainer = t;
		t.elementsContent = [this.backgroundColorContainer_i(),this.backgroundGroup_i(),this.stageGroup_i(),this.tipLayerContainer_i(),this.bleedTipRect_i(),this.pageBorderRect_i()];
		return t;
	};
	_proto.backgroundColorContainer_i = function () {
		var t = new eui.Group();
		this.backgroundColorContainer = t;
		t.bottom = 1;
		t.left = 1;
		t.right = 1;
		t.top = 1;
		t.elementsContent = [this._Rect1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.backgroundGroup_i = function () {
		var t = new eui.Group();
		this.backgroundGroup = t;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		return t;
	};
	_proto.tipLayerContainer_i = function () {
		var t = new eui.Group();
		this.tipLayerContainer = t;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.bleedTipRect_i = function () {
		var t = new eui.Rect();
		this.bleedTipRect = t;
		t.alpha = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.pageBorderRect_i = function () {
		var t = new eui.Rect();
		this.pageBorderRect = t;
		t.bottom = 1;
		t.fillAlpha = 0;
		t.left = 1;
		t.right = 1;
		t.strokeColor = 0x999999;
		t.top = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topLayerContainer_i = function () {
		var t = new eui.Group();
		this.topLayerContainer = t;
		return t;
	};
	return ChildStageOverviewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/CircleLabelSkin.exml'] = window.app.CircleLabelSkin = (function (_super) {
	__extends(CircleLabelSkin, _super);
	function CircleLabelSkin() {
		_super.call(this);
		this.skinParts = ["ellipseRect","labelDisplay"];
		
		this.elementsContent = [this._Group1_i()];
	}
	var _proto = CircleLabelSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this.ellipseRect_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto.ellipseRect_i = function () {
		var t = new eui.Rect();
		this.ellipseRect = t;
		t.bottom = 0;
		t.fillColor = 0xFC344D;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		return t;
	};
	return CircleLabelSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/DecorationElementSkin.exml'] = window.app.DecorationElementSkin = (function (_super) {
	__extends(DecorationElementSkin, _super);
	function DecorationElementSkin() {
		_super.call(this);
		this.skinParts = ["loadingDisplay","imageComp"];
		
		this.elementsContent = [this._Group1_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","_Group1",0,"")
				])
		];
	}
	var _proto = DecorationElementSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.elementsContent = [this.imageComp_i()];
		return t;
	};
	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	return DecorationElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/FaceThicknessSkin.exml'] = window.app.FaceThicknessSkin = (function (_super) {
	__extends(FaceThicknessSkin, _super);
	function FaceThicknessSkin() {
		_super.call(this);
		this.skinParts = ["leftLine","spacer","rightLine"];
		
		this.elementsContent = [this._Group1_i()];
	}
	var _proto = FaceThicknessSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.leftLine_i(),this.spacer_i(),this.rightLine_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		return t;
	};
	_proto.leftLine_i = function () {
		var t = new eui.Rect();
		this.leftLine = t;
		t.fillColor = 0x999999;
		t.percentHeight = 100;
		return t;
	};
	_proto.spacer_i = function () {
		var t = new eui.Group();
		this.spacer = t;
		return t;
	};
	_proto.rightLine_i = function () {
		var t = new eui.Rect();
		this.rightLine = t;
		t.fillColor = 0x999999;
		t.percentHeight = 100;
		return t;
	};
	return FaceThicknessSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/LocationSelectionDDLSkin.exml'] = window.app.LocationSelectionDDLSkin = (function (_super) {
	__extends(LocationSelectionDDLSkin, _super);
	var LocationSelectionDDLSkin$Skin3 = 	(function (_super) {
		__extends(LocationSelectionDDLSkin$Skin3, _super);
		function LocationSelectionDDLSkin$Skin3() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Label1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1)
					])
				,
				new eui.State ("selected",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
			];
			
			eui.Binding.$bindProperties(this, ["hostComponent.data.name"],[0],this._Label1,"text");
		}
		var _proto = LocationSelectionDDLSkin$Skin3.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.fillAlpha = 1;
			t.fillColor = 0xFFFFFF;
			t.height = 30;
			t.left = 0;
			t.right = 0;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			this._Label1 = t;
			t.style = "cd_label";
			t.left = 5;
			t.size = 12;
			t.textColor = 0x000000;
			t.verticalCenter = 0;
			return t;
		};
		return LocationSelectionDDLSkin$Skin3;
	})(eui.Skin);

	var LocationSelectionDDLSkin$Skin4 = 	(function (_super) {
		__extends(LocationSelectionDDLSkin$Skin4, _super);
		function LocationSelectionDDLSkin$Skin4() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Rect1_i(),this.labelDisplay_i(),this._Rect2_i(),this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = LocationSelectionDDLSkin$Skin4.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 1;
			t.fillColor = 0xFFFFFF;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xBCBCBC;
			t.strokeWeight = 1;
			t.top = 0;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.left = 5;
			t.right = 32;
			t.size = 12;
			t.textAlign = "center";
			t.verticalCenter = 0;
			return t;
		};
		_proto._Rect2_i = function () {
			var t = new eui.Rect();
			t.fillColor = 0xcccccc;
			t.percentHeight = 100;
			t.right = 28;
			t.verticalCenter = 0;
			t.width = 1;
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.right = 5;
			t.source = "icon_dropdown_down_png";
			t.touchEnabled = false;
			t.verticalCenter = 0;
			return t;
		};
		return LocationSelectionDDLSkin$Skin4;
	})(eui.Skin);

	function LocationSelectionDDLSkin() {
		_super.call(this);
		this.skinParts = ["list","scroller","mainButton"];
		
		this.elementsContent = [this.scroller_i(),this.mainButton_i()];
	}
	var _proto = LocationSelectionDDLSkin.prototype;

	_proto.scroller_i = function () {
		var t = new eui.Scroller();
		this.scroller = t;
		t.bounces = false;
		t.includeInLayout = true;
		t.scrollPolicyV = "auto";
		t.percentWidth = 100;
		t.viewport = this.list_i();
		return t;
	};
	_proto.list_i = function () {
		var t = new eui.List();
		this.list = t;
		t.requireSelection = true;
		t.selectedIndex = 0;
		t.percentWidth = 100;
		t.itemRendererSkinName = LocationSelectionDDLSkin$Skin3;
		return t;
	};
	_proto.mainButton_i = function () {
		var t = new eui.Button();
		this.mainButton = t;
		t.height = 30;
		t.minWidth = 110;
		t.percentWidth = 100;
		t.skinName = LocationSelectionDDLSkin$Skin4;
		return t;
	};
	return LocationSelectionDDLSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/LocationSelectionBarSkin.exml'] = window.app.LocationSelectionBarSkin = (function (_super) {
	__extends(LocationSelectionBarSkin, _super);
	function LocationSelectionBarSkin() {
		_super.call(this);
		this.skinParts = ["provinceCB","cityCB","districtCB"];
		
		this.elementsContent = [this._Group1_i()];
	}
	var _proto = LocationSelectionBarSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.provinceCB_i(),this.cityCB_i(),this.districtCB_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 7;
		return t;
	};
	_proto.provinceCB_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.provinceCB = t;
		t.skinName = "app.LocationSelectionDDLSkin";
		t.width = 150;
		return t;
	};
	_proto.cityCB_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.cityCB = t;
		t.skinName = "app.LocationSelectionDDLSkin";
		t.width = 130;
		return t;
	};
	_proto.districtCB_i = function () {
		var t = new cdcommon.framework.components.DropDownList();
		this.districtCB = t;
		t.skinName = "app.LocationSelectionDDLSkin";
		t.width = 130;
		return t;
	};
	return LocationSelectionBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/MaterialPanelSkin.exml'] = window.app.MaterialPanelSkin = (function (_super) {
	__extends(MaterialPanelSkin, _super);
	var MaterialPanelSkin$Skin5 = 	(function (_super) {
		__extends(MaterialPanelSkin$Skin5, _super);
		function MaterialPanelSkin$Skin5() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Rect1_i(),this.labelDisplay_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1)
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = MaterialPanelSkin$Skin5.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 10;
			t.ellipseWidth = 10;
			t.fillAlpha = 0;
			t.fillColor = 0xF2F2F2;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xFC344D;
			t.strokeWeight = 1;
			t.top = 0;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.horizontalCenter = 0;
			t.size = 14;
			t.textAlign = "center";
			t.textColor = 0xFC344D;
			t.verticalCenter = 0;
			return t;
		};
		return MaterialPanelSkin$Skin5;
	})(eui.Skin);

	var MaterialPanelSkin$Skin6 = 	(function (_super) {
		__extends(MaterialPanelSkin$Skin6, _super);
		function MaterialPanelSkin$Skin6() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i(),this._Rect1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("selected",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
			];
			
			eui.Binding.$bindProperties(this, ["hostComponent.data.thumb_url"],[0],this._Image1,"source");
		}
		var _proto = MaterialPanelSkin$Skin6.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.fillMode = "scale";
			t.height = 70;
			t.smoothing = true;
			t.width = 70;
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.fillAlpha = 0;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xFC344D;
			t.strokeWeight = 2;
			t.top = 0;
			t.touchEnabled = false;
			t.visible = false;
			return t;
		};
		return MaterialPanelSkin$Skin6;
	})(eui.Skin);

	var MaterialPanelSkin$Skin7 = 	(function (_super) {
		__extends(MaterialPanelSkin$Skin7, _super);
		function MaterialPanelSkin$Skin7() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i(),this._Rect1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("selected",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Rect1","visible",true)
					])
			];
			
			eui.Binding.$bindProperties(this, ["hostComponent.data.thumb_url"],[0],this._Image1,"source");
		}
		var _proto = MaterialPanelSkin$Skin7.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 80;
			t.smoothing = true;
			t.width = 80;
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.fillAlpha = 0;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xFC344D;
			t.strokeWeight = 2;
			t.top = 0;
			t.touchEnabled = false;
			t.visible = false;
			return t;
		};
		return MaterialPanelSkin$Skin7;
	})(eui.Skin);

	var MaterialPanelSkin$Skin8 = 	(function (_super) {
		__extends(MaterialPanelSkin$Skin8, _super);
		function MaterialPanelSkin$Skin8() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
			];
		}
		var _proto = MaterialPanelSkin$Skin8.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.gap = 5;
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 30;
			t.smoothing = true;
			t.source = "icon_background_png";
			t.width = 30;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 18;
			return t;
		};
		return MaterialPanelSkin$Skin8;
	})(eui.Skin);

	var MaterialPanelSkin$Skin9 = 	(function (_super) {
		__extends(MaterialPanelSkin$Skin9, _super);
		function MaterialPanelSkin$Skin9() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("labelDisplay","bold",true)
					])
			];
		}
		var _proto = MaterialPanelSkin$Skin9.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.gap = 5;
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 30;
			t.smoothing = true;
			t.source = "icon_layout_png";
			t.width = 30;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 18;
			return t;
		};
		return MaterialPanelSkin$Skin9;
	})(eui.Skin);

	function MaterialPanelSkin() {
		_super.call(this);
		this.skinParts = ["backgroundSwitchButton","backgroundListComp","backgroundListScroller","backgroundListView","layoutListComp","layoutListScroller","layoutListView","viewStack","backgroundToggleButton","layoutToggleButton","togglesGroup"];
		
		this.elementsContent = [this.viewStack_i(),this.togglesGroup_i()];
	}
	var _proto = MaterialPanelSkin.prototype;

	_proto.viewStack_i = function () {
		var t = new eui.ViewStack();
		this.viewStack = t;
		t.bottom = 60;
		t.height = 90;
		t.includeInLayout = false;
		t.left = 0;
		t.right = 0;
		t.visible = false;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this.backgroundListView_i(),this.layoutListView_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto.backgroundListView_i = function () {
		var t = new eui.Group();
		this.backgroundListView = t;
		t.percentHeight = 100;
		t.name = "background";
		t.percentWidth = 100;
		t.elementsContent = [this._Rect1_i(),this.backgroundSwitchButton_i(),this.backgroundListScroller_i(),this._Rect2_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.backgroundSwitchButton_i = function () {
		var t = new eui.Button();
		this.backgroundSwitchButton = t;
		t.height = 70;
		t.label = "换一组";
		t.left = 10;
		t.verticalCenter = 0;
		t.width = 70;
		t.skinName = MaterialPanelSkin$Skin5;
		return t;
	};
	_proto.backgroundListScroller_i = function () {
		var t = new eui.Scroller();
		this.backgroundListScroller = t;
		t.bounces = false;
		t.left = 95;
		t.right = 0;
		t.scrollPolicyV = "off";
		t.throwSpeed = 0;
		t.verticalCenter = 0;
		t.viewport = this.backgroundListComp_i();
		return t;
	};
	_proto.backgroundListComp_i = function () {
		var t = new eui.List();
		this.backgroundListComp = t;
		t.requireSelection = false;
		t.useVirtualLayout = true;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.itemRendererSkinName = MaterialPanelSkin$Skin6;
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.layoutListView_i = function () {
		var t = new eui.Group();
		this.layoutListView = t;
		t.percentHeight = 100;
		t.name = "layout";
		t.percentWidth = 100;
		t.elementsContent = [this._Rect3_i(),this.layoutListScroller_i(),this._Rect4_i()];
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.layoutListScroller_i = function () {
		var t = new eui.Scroller();
		this.layoutListScroller = t;
		t.bounces = false;
		t.left = 15;
		t.right = 0;
		t.scrollPolicyV = "off";
		t.throwSpeed = 0;
		t.verticalCenter = 0;
		t.viewport = this.layoutListComp_i();
		return t;
	};
	_proto.layoutListComp_i = function () {
		var t = new eui.List();
		this.layoutListComp = t;
		t.requireSelection = false;
		t.useVirtualLayout = true;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		t.itemRendererSkinName = MaterialPanelSkin$Skin7;
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		return t;
	};
	_proto._Rect4_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.togglesGroup_i = function () {
		var t = new eui.Group();
		this.togglesGroup = t;
		t.bottom = 0;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.layout = this._HorizontalLayout3_i();
		t.elementsContent = [this.backgroundToggleButton_i(),this._Rect5_i(),this.layoutToggleButton_i()];
		return t;
	};
	_proto._HorizontalLayout3_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.backgroundToggleButton_i = function () {
		var t = new eui.ToggleButton();
		this.backgroundToggleButton = t;
		t.percentHeight = 100;
		t.label = "背景";
		t.percentWidth = 100;
		t.skinName = MaterialPanelSkin$Skin8;
		return t;
	};
	_proto._Rect5_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xdedede;
		t.height = 40;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 1;
		return t;
	};
	_proto.layoutToggleButton_i = function () {
		var t = new eui.ToggleButton();
		this.layoutToggleButton = t;
		t.percentHeight = 100;
		t.label = "布局";
		t.percentWidth = 100;
		t.skinName = MaterialPanelSkin$Skin9;
		return t;
	};
	return MaterialPanelSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PageEditorViewSkin.exml'] = window.app.PageEditorViewSkin = (function (_super) {
	__extends(PageEditorViewSkin, _super);
	var PageEditorViewSkin$Skin10 = 	(function (_super) {
		__extends(PageEditorViewSkin$Skin10, _super);
		function PageEditorViewSkin$Skin10() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PageEditorViewSkin$Skin10.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.gap = 5;
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 20;
			t.smoothing = true;
			t.source = "btn_pho_delete_r_png";
			t.width = 20;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.style = "cd_label";
			t.size = 12;
			t.text = "删除照片";
			return t;
		};
		return PageEditorViewSkin$Skin10;
	})(eui.Skin);

	var PageEditorViewSkin$Skin11 = 	(function (_super) {
		__extends(PageEditorViewSkin$Skin11, _super);
		function PageEditorViewSkin$Skin11() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group2","alpha",0.45)
					])
			];
		}
		var _proto = PageEditorViewSkin$Skin11.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			this._Group2 = t;
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0x959595;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PageEditorViewSkin$Skin11;
	})(eui.Skin);

	var PageEditorViewSkin$Skin12 = 	(function (_super) {
		__extends(PageEditorViewSkin$Skin12, _super);
		function PageEditorViewSkin$Skin12() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PageEditorViewSkin$Skin12.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.smoothing = true;
			t.source = "btn_prev_page_png";
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.style = "cd_label";
			t.size = 18;
			t.text = "上一页";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PageEditorViewSkin$Skin12;
	})(eui.Skin);

	var PageEditorViewSkin$Skin13 = 	(function (_super) {
		__extends(PageEditorViewSkin$Skin13, _super);
		function PageEditorViewSkin$Skin13() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PageEditorViewSkin$Skin13.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.smoothing = true;
			t.source = "btn_next_page_png";
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.style = "cd_label";
			t.size = 18;
			t.text = "下一页";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PageEditorViewSkin$Skin13;
	})(eui.Skin);

	function PageEditorViewSkin() {
		_super.call(this);
		this.skinParts = ["clipRadioButtonGroup","deleteButton","closeButton","stageGroup","previousPageButton","nextPageButton","overviewGroup"];
		
		this.clipRadioButtonGroup_i();
		this.elementsContent = [this.overviewGroup_i()];
		
		eui.Binding.$bindProperties(this, ["clipRadioButtonGroup"],[0],this._RadioButton1,"group");
		eui.Binding.$bindProperties(this, ["clipRadioButtonGroup"],[0],this._RadioButton2,"group");
	}
	var _proto = PageEditorViewSkin.prototype;

	_proto.clipRadioButtonGroup_i = function () {
		var t = new eui.RadioButtonGroup();
		this.clipRadioButtonGroup = t;
		return t;
	};
	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this._Rect1_i(),this._Rect2_i(),this._Group5_i(),this.stageGroup_i(),this.previousPageButton_i(),this.nextPageButton_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xF2F2F2;
		t.height = 50;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0x727272;
		t.left = 0;
		t.right = 0;
		t.top = 50;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group5_i = function () {
		var t = new eui.Group();
		t.height = 50;
		t.percentWidth = 100;
		t.elementsContent = [this._Group3_i(),this._Group4_i()];
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Group2_i(),this._Rect3_i(),this.deleteButton_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 25;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout2_i();
		t.elementsContent = [this._Image1_i(),this._RadioButton1_i(),this._Group1_i(),this._Image2_i(),this._RadioButton2_i()];
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 5;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.height = 20;
		t.smoothing = true;
		t.source = "icon_pho_white_space_red_png";
		t.touchEnabled = false;
		t.width = 20;
		return t;
	};
	_proto._RadioButton1_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton1 = t;
		t.label = "保留图片（可能含白边）";
		t.value = "0";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 25;
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		t.height = 20;
		t.smoothing = true;
		t.source = "icon_pho_cut_red_png";
		t.touchEnabled = false;
		t.width = 20;
		return t;
	};
	_proto._RadioButton2_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton2 = t;
		t.label = "裁剪图片（不含白边）";
		t.value = "1";
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xc9c9c9;
		t.height = 30;
		t.horizontalCenter = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.verticalCenter = 0;
		t.width = 1;
		return t;
	};
	_proto.deleteButton_i = function () {
		var t = new eui.Button();
		this.deleteButton = t;
		t.skinName = PageEditorViewSkin$Skin10;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		t.right = 25;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout3_i();
		t.elementsContent = [this.closeButton_i()];
		return t;
	};
	_proto._HorizontalLayout3_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 15;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.height = 40;
		t.label = "返回";
		t.width = 100;
		t.skinName = PageEditorViewSkin$Skin11;
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.bottom = 20;
		t.left = 20;
		t.right = 20;
		t.scrollEnabled = true;
		t.top = 70;
		return t;
	};
	_proto.previousPageButton_i = function () {
		var t = new eui.Button();
		this.previousPageButton = t;
		t.left = 65;
		t.verticalCenter = 0;
		t.skinName = PageEditorViewSkin$Skin12;
		return t;
	};
	_proto.nextPageButton_i = function () {
		var t = new eui.Button();
		this.nextPageButton = t;
		t.right = 65;
		t.verticalCenter = 0;
		t.skinName = PageEditorViewSkin$Skin13;
		return t;
	};
	return PageEditorViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PageItemContainerSkin.exml'] = window.app.PageItemContainerSkin = (function (_super) {
	__extends(PageItemContainerSkin, _super);
	var PageItemContainerSkin$Skin14 = 	(function (_super) {
		__extends(PageItemContainerSkin$Skin14, _super);
		function PageItemContainerSkin$Skin14() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PageItemContainerSkin$Skin14.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 25;
			t.smoothing = true;
			t.source = "btn_decrease_png";
			t.width = 25;
			return t;
		};
		return PageItemContainerSkin$Skin14;
	})(eui.Skin);

	var PageItemContainerSkin$Skin15 = 	(function (_super) {
		__extends(PageItemContainerSkin$Skin15, _super);
		function PageItemContainerSkin$Skin15() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PageItemContainerSkin$Skin15.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 25;
			t.smoothing = true;
			t.source = "btn_increase_png";
			t.width = 25;
			return t;
		};
		return PageItemContainerSkin$Skin15;
	})(eui.Skin);

	var PageItemContainerSkin$Skin16 = 	(function (_super) {
		__extends(PageItemContainerSkin$Skin16, _super);
		function PageItemContainerSkin$Skin16() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.5)
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Image1","source","icon_cb_selected_png")
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Image1","source","icon_cb_selected_png")
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Image1","source","icon_cb_selected_png")
					])
			];
		}
		var _proto = PageItemContainerSkin$Skin16.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.percentHeight = 100;
			t.percentWidth = 100;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.alpha = 1;
			t.source = "icon_cb_unselected_png";
			return t;
		};
		return PageItemContainerSkin$Skin16;
	})(eui.Skin);

	function PageItemContainerSkin() {
		_super.call(this);
		this.skinParts = ["bgRect","stageGroup","cutTypeDisplay","decreaseCountButton","printCountInput","increaseCountButton","overviewGroup","selectCheckBox"];
		
		this.elementsContent = [this.overviewGroup_i()];
		this.bgRect_i();
		
		this.selectCheckBox_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("selectionMode",
				[
					new eui.AddItems("selectCheckBox","",1,"")
				])
			,
			new eui.State ("selected",
				[
					new eui.AddItems("bgRect","",2,"overviewGroup"),
					new eui.AddItems("selectCheckBox","",1,"")
				])
		];
	}
	var _proto = PageItemContainerSkin.prototype;

	_proto.bgRect_i = function () {
		var t = new eui.Rect();
		this.bgRect = t;
		t.bottom = -15;
		t.ellipseHeight = 10;
		t.ellipseWidth = 10;
		t.fillColor = 0xfffecd;
		t.left = -15;
		t.right = -15;
		t.strokeColor = 0xb4b4b4;
		t.strokeWeight = 2;
		t.top = -15;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.stageGroup_i(),this.cutTypeDisplay_i(),this._Group1_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 5;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	_proto.cutTypeDisplay_i = function () {
		var t = new eui.Label();
		this.cutTypeDisplay = t;
		t.style = "cd_label";
		t.height = 20;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.decreaseCountButton_i(),this.printCountInput_i(),this.increaseCountButton_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.decreaseCountButton_i = function () {
		var t = new eui.Button();
		this.decreaseCountButton = t;
		t.skinName = PageItemContainerSkin$Skin14;
		return t;
	};
	_proto.printCountInput_i = function () {
		var t = new eui.EditableText();
		this.printCountInput = t;
		t.background = true;
		t.backgroundColor = 0xFFFFFF;
		t.border = true;
		t.borderColor = 0x444444;
		t.height = 25;
		t.size = 14;
		t.text = "1";
		t.textAlign = "center";
		t.textColor = 0x444444;
		t.touchEnabled = false;
		t.verticalAlign = "middle";
		t.width = 50;
		return t;
	};
	_proto.increaseCountButton_i = function () {
		var t = new eui.Button();
		this.increaseCountButton = t;
		t.skinName = PageItemContainerSkin$Skin15;
		return t;
	};
	_proto.selectCheckBox_i = function () {
		var t = new eui.CheckBox();
		this.selectCheckBox = t;
		t.right = -12;
		t.top = -12;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.skinName = PageItemContainerSkin$Skin16;
		return t;
	};
	return PageItemContainerSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PageListSkin.exml'] = window.app.PageListSkin = (function (_super) {
	__extends(PageListSkin, _super);
	function PageListSkin() {
		_super.call(this);
		this.skinParts = ["listGroup","scroller"];
		
		this.elementsContent = [this.scroller_i()];
	}
	var _proto = PageListSkin.prototype;

	_proto.scroller_i = function () {
		var t = new eui.Scroller();
		this.scroller = t;
		t.bottom = 20;
		t.bounces = false;
		t.left = 20;
		t.right = 0;
		t.scrollPolicyH = "off";
		t.top = 20;
		t.viewport = this.listGroup_i();
		return t;
	};
	_proto.listGroup_i = function () {
		var t = new eui.Group();
		this.listGroup = t;
		t.scrollEnabled = true;
		return t;
	};
	return PageListSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PhotoEditorViewSkin.exml'] = window.app.PhotoEditorViewSkin = (function (_super) {
	__extends(PhotoEditorViewSkin, _super);
	var PhotoEditorViewSkin$Skin17 = 	(function (_super) {
		__extends(PhotoEditorViewSkin$Skin17, _super);
		function PhotoEditorViewSkin$Skin17() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1),
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PhotoEditorViewSkin$Skin17.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 0;
			t.fillColor = 0x000000;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xffffff;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 40;
			t.smoothing = true;
			t.source = "btn_pho_switch_png";
			t.width = 40;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.size = 12;
			t.text = "替换照片";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PhotoEditorViewSkin$Skin17;
	})(eui.Skin);

	var PhotoEditorViewSkin$Skin18 = 	(function (_super) {
		__extends(PhotoEditorViewSkin$Skin18, _super);
		function PhotoEditorViewSkin$Skin18() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1),
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PhotoEditorViewSkin$Skin18.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 0;
			t.fillColor = 0x000000;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xffffff;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 40;
			t.smoothing = true;
			t.source = "btn_pho_left_rotate_png";
			t.width = 40;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.size = 12;
			t.text = "左旋转";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PhotoEditorViewSkin$Skin18;
	})(eui.Skin);

	var PhotoEditorViewSkin$Skin19 = 	(function (_super) {
		__extends(PhotoEditorViewSkin$Skin19, _super);
		function PhotoEditorViewSkin$Skin19() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1),
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PhotoEditorViewSkin$Skin19.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 0;
			t.fillColor = 0x000000;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xffffff;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 40;
			t.smoothing = true;
			t.source = "btn_pho_right_rotate_png";
			t.width = 40;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.size = 12;
			t.text = "右旋转";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PhotoEditorViewSkin$Skin19;
	})(eui.Skin);

	var PhotoEditorViewSkin$Skin20 = 	(function (_super) {
		__extends(PhotoEditorViewSkin$Skin20, _super);
		function PhotoEditorViewSkin$Skin20() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1),
						new eui.SetProperty("_Image1","alpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Image1","alpha",0.35)
					])
			];
		}
		var _proto = PhotoEditorViewSkin$Skin20.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 0;
			t.fillColor = 0x000000;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0xffffff;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.bottom = 5;
			t.left = 5;
			t.right = 5;
			t.top = 5;
			t.layout = this._VerticalLayout1_i();
			t.elementsContent = [this._Image1_i(),this._Label1_i()];
			return t;
		};
		_proto._VerticalLayout1_i = function () {
			var t = new eui.VerticalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			this._Image1 = t;
			t.height = 40;
			t.smoothing = true;
			t.source = "btn_pho_delete_png";
			t.width = 40;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			t.size = 12;
			t.text = "卸下照片";
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PhotoEditorViewSkin$Skin20;
	})(eui.Skin);

	function PhotoEditorViewSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","stageGroup","closeButton","submitButton","topGroup","phoSwitchButton","phoLeftRotateButton","phoRightRotateButton","phoClearButton","bottomGroup","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
	}
	var _proto = PhotoEditorViewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this.backgroundRect_i(),this.stageGroup_i(),this.topGroup_i(),this.bottomGroup_i()];
		return t;
	};
	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0x000000;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.bottom = 120;
		t.left = 40;
		t.right = 40;
		t.top = 80;
		t.layout = this._BasicLayout1_i();
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout2_i();
		t.elementsContent = [this._Rect1_i(),this.closeButton_i(),this._Label1_i(),this.submitButton_i()];
		return t;
	};
	_proto._BasicLayout2_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xffffff;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.height = 35;
		t.label = "返回";
		t.left = 20;
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.horizontalCenter = 0;
		t.size = 16;
		t.text = "图片框编辑";
		t.verticalCenter = 0;
		return t;
	};
	_proto.submitButton_i = function () {
		var t = new eui.Button();
		this.submitButton = t;
		t.height = 35;
		t.label = "确定";
		t.right = 20;
		t.skinName = "app.OKButtonSkin";
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto.bottomGroup_i = function () {
		var t = new eui.Group();
		this.bottomGroup = t;
		t.bottom = 0;
		t.height = 100;
		t.left = 0;
		t.right = 0;
		t.layout = this._BasicLayout3_i();
		t.elementsContent = [this._Rect2_i(),this._Group1_i()];
		return t;
	};
	_proto._BasicLayout3_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0x1C1F24;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.left = 0;
		t.right = 0;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.phoSwitchButton_i(),this.phoLeftRotateButton_i(),this.phoRightRotateButton_i(),this.phoClearButton_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 50;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.phoSwitchButton_i = function () {
		var t = new eui.Button();
		this.phoSwitchButton = t;
		t.skinName = PhotoEditorViewSkin$Skin17;
		return t;
	};
	_proto.phoLeftRotateButton_i = function () {
		var t = new eui.Button();
		this.phoLeftRotateButton = t;
		t.skinName = PhotoEditorViewSkin$Skin18;
		return t;
	};
	_proto.phoRightRotateButton_i = function () {
		var t = new eui.Button();
		this.phoRightRotateButton = t;
		t.skinName = PhotoEditorViewSkin$Skin19;
		return t;
	};
	_proto.phoClearButton_i = function () {
		var t = new eui.Button();
		this.phoClearButton = t;
		t.skinName = PhotoEditorViewSkin$Skin20;
		return t;
	};
	return PhotoEditorViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PhotoElementSkin.exml'] = window.app.PhotoElementSkin = (function (_super) {
	__extends(PhotoElementSkin, _super);
	function PhotoElementSkin() {
		_super.call(this);
		this.skinParts = ["frameMaskImageComp","imageComp","emptyFlag","dragIndicator","borderRect","frameImageComp","scaleFlagLabel","contentGroup"];
		
		this.elementsContent = [this.contentGroup_i()];
		this.emptyFlag_i();
		
		this.scaleFlagLabel_i();
		
		this.states = [
			new eui.State ("normal",
				[
					new eui.AddItems("emptyFlag","contentGroup",2,"dragIndicator")
				])
			,
			new eui.State ("editing",
				[
					new eui.SetProperty("frameMaskImageComp","visible",false),
					new eui.SetProperty("frameImageComp","visible",false)
				])
			,
			new eui.State ("filledPhoto",
				[
					new eui.AddItems("scaleFlagLabel","contentGroup",1,"")
				])
		];
	}
	var _proto = PhotoElementSkin.prototype;

	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this.frameMaskImageComp_i(),this.imageComp_i(),this.dragIndicator_i(),this.borderRect_i(),this.frameImageComp_i()];
		return t;
	};
	_proto.frameMaskImageComp_i = function () {
		var t = new eui.Image();
		this.frameMaskImageComp = t;
		t.touchEnabled = false;
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.smoothing = true;
		return t;
	};
	_proto.emptyFlag_i = function () {
		var t = new eui.Image();
		this.emptyFlag = t;
		t.height = 200;
		t.horizontalCenter = 0;
		t.source = "icon_pho_mask_png";
		t.touchEnabled = false;
		t.verticalCenter = 0;
		t.width = 200;
		return t;
	};
	_proto.dragIndicator_i = function () {
		var t = new eui.Rect();
		this.dragIndicator = t;
		t.alpha = 0.2;
		t.bottom = 0;
		t.fillColor = 0x00FF00;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.visible = false;
		return t;
	};
	_proto.borderRect_i = function () {
		var t = new eui.Rect();
		this.borderRect = t;
		t.bottom = -1;
		t.left = -1;
		t.right = -1;
		t.top = -1;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.visible = false;
		return t;
	};
	_proto.frameImageComp_i = function () {
		var t = new eui.Image();
		this.frameImageComp = t;
		t.touchEnabled = false;
		return t;
	};
	_proto.scaleFlagLabel_i = function () {
		var t = new eui.Label();
		this.scaleFlagLabel = t;
		t.alpha = 0;
		t.bold = true;
		t.horizontalCenter = 0;
		t.size = 26;
		t.textColor = 0xFFFFFF;
		t.touchEnabled = false;
		t.verticalCenter = 0;
		return t;
	};
	return PhotoElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PhotoItemRendererSkin.exml'] = window.app.PhotoItemRendererSkin = (function (_super) {
	__extends(PhotoItemRendererSkin, _super);
	var PhotoItemRendererSkin$Skin21 = 	(function (_super) {
		__extends(PhotoItemRendererSkin$Skin21, _super);
		function PhotoItemRendererSkin$Skin21() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PhotoItemRendererSkin$Skin21.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.elementsContent = [this._Image1_i()];
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 20;
			t.smoothing = true;
			t.source = "btn_trash_w_png";
			t.width = 20;
			return t;
		};
		return PhotoItemRendererSkin$Skin21;
	})(eui.Skin);

	var PhotoItemRendererSkin$Skin22 = 	(function (_super) {
		__extends(PhotoItemRendererSkin$Skin22, _super);
		function PhotoItemRendererSkin$Skin22() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PhotoItemRendererSkin$Skin22.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.elementsContent = [this._Image1_i()];
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 20;
			t.smoothing = true;
			t.source = "btn_repeat_w_png";
			t.width = 20;
			return t;
		};
		return PhotoItemRendererSkin$Skin22;
	})(eui.Skin);

	function PhotoItemRendererSkin() {
		_super.call(this);
		this.skinParts = ["imageMask","imageComp","progressMask","deleteButton","reUploadButton","stateLabel","borderRect","usedFlagImage"];
		
		this.elementsContent = [this._Group3_i(),this.borderRect_i(),this.usedFlagImage_i()];
		this.progressMask_i();
		
		this.deleteButton_i();
		
		this.reUploadButton_i();
		
		this.stateLabel_i();
		
		this.states = [
			new eui.State ("up",
				[
					new eui.AddItems("stateLabel","_Group2",1,"")
				])
			,
			new eui.State ("down",
				[
					new eui.AddItems("stateLabel","_Group2",1,"")
				])
			,
			new eui.State ("disabled",
				[
					new eui.AddItems("stateLabel","_Group2",1,"")
				])
			,
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("waiting",
				[
					new eui.AddItems("progressMask","_Group3",2,"_Group2"),
					new eui.AddItems("deleteButton","_Group1",1,""),
					new eui.AddItems("stateLabel","_Group2",1,"")
				])
			,
			new eui.State ("uploading",
				[
					new eui.AddItems("progressMask","_Group3",2,"_Group2"),
					new eui.AddItems("stateLabel","_Group2",1,"")
				])
			,
			new eui.State ("error",
				[
					new eui.AddItems("progressMask","_Group3",2,"_Group2"),
					new eui.AddItems("deleteButton","_Group1",1,""),
					new eui.AddItems("reUploadButton","_Group1",1,""),
					new eui.AddItems("stateLabel","_Group2",1,""),
					new eui.SetProperty("progressMask","fillColor",0xFF0000)
				])
		];
		
		eui.Binding.$bindProperties(this, ["imageMask"],[0],this.imageComp,"mask");
	}
	var _proto = PhotoItemRendererSkin.prototype;

	_proto._Group3_i = function () {
		var t = new eui.Group();
		this._Group3 = t;
		t.bottom = 1.5;
		t.left = 1.5;
		t.right = 1.5;
		t.top = 1.5;
		t.elementsContent = [this.imageMask_i(),this.imageComp_i(),this._Group2_i()];
		return t;
	};
	_proto.imageMask_i = function () {
		var t = new eui.Rect();
		this.imageMask = t;
		t.bottom = 0;
		t.fillColor = 0x999999;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.smoothing = true;
		return t;
	};
	_proto.progressMask_i = function () {
		var t = new eui.Rect();
		this.progressMask = t;
		t.alpha = 0.35;
		t.bottom = 0;
		t.fillColor = 0x000000;
		t.left = 0;
		t.right = 0;
		t.strokeAlpha = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		this._Group2 = t;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Group1_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 10;
		t.horizontalAlign = "center";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto.deleteButton_i = function () {
		var t = new eui.Button();
		this.deleteButton = t;
		t.label = "删除";
		t.skinName = PhotoItemRendererSkin$Skin21;
		return t;
	};
	_proto.reUploadButton_i = function () {
		var t = new eui.Button();
		this.reUploadButton = t;
		t.label = "重传";
		t.skinName = PhotoItemRendererSkin$Skin22;
		return t;
	};
	_proto.stateLabel_i = function () {
		var t = new eui.Label();
		this.stateLabel = t;
		t.size = 12;
		t.textColor = 0xFFFFFF;
		t.touchEnabled = false;
		return t;
	};
	_proto.borderRect_i = function () {
		var t = new eui.Rect();
		this.borderRect = t;
		t.bottom = 0;
		t.fillAlpha = 0;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0xdedede;
		t.strokeWeight = 2;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.usedFlagImage_i = function () {
		var t = new eui.Image();
		this.usedFlagImage = t;
		t.includeInLayout = false;
		t.right = 7;
		t.source = "icon_used_png";
		t.top = 7;
		t.touchEnabled = false;
		t.visible = false;
		return t;
	};
	return PhotoItemRendererSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PhotoManagerPanelSkin.exml'] = window.app.PhotoManagerPanelSkin = (function (_super) {
	__extends(PhotoManagerPanelSkin, _super);
	var PhotoManagerPanelSkin$Skin23 = 	(function (_super) {
		__extends(PhotoManagerPanelSkin$Skin23, _super);
		function PhotoManagerPanelSkin$Skin23() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PhotoManagerPanelSkin$Skin23.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.source = "btn_win_close_png";
			return t;
		};
		return PhotoManagerPanelSkin$Skin23;
	})(eui.Skin);

	var PhotoManagerPanelSkin$Skin24 = 	(function (_super) {
		__extends(PhotoManagerPanelSkin$Skin24, _super);
		function PhotoManagerPanelSkin$Skin24() {
			_super.call(this);
			this.skinParts = [];
			
			this.height = 35;
			this.width = 126;
			this.elementsContent = [this._Rect1_i(),this._Rect2_i(),this._Rect3_i(),this._Rect4_i(),this._Label1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Label1","bold",true)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Label1","bold",true)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Label1","bold",true)
					])
			];
			
			eui.Binding.$bindProperties(this, ["hostComponent.data"],[0],this._Label1,"text");
		}
		var _proto = PhotoManagerPanelSkin$Skin24.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.fillColor = 0xF3F3F3;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Rect2_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.fillColor = 0xC9C9C9;
			t.left = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			t.width = 1;
			return t;
		};
		_proto._Rect3_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.fillColor = 0xC9C9C9;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			t.width = 1;
			return t;
		};
		_proto._Rect4_i = function () {
			var t = new eui.Rect();
			t.fillColor = 0xC9C9C9;
			t.height = 1;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			this._Label1 = t;
			t.style = "cd_label";
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			return t;
		};
		return PhotoManagerPanelSkin$Skin24;
	})(eui.Skin);

	function PhotoManagerPanelSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","moveArea","titleDisplay","closeButton","tabBar","topGroup","viewStack"];
		
		this.elementsContent = [this.backgroundRect_i(),this.topGroup_i(),this.viewStack_i()];
		
		eui.Binding.$bindProperties(this, ["viewStack"],[0],this.tabBar,"dataProvider");
	}
	var _proto = PhotoManagerPanelSkin.prototype;

	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 100;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect1_i(),this._Rect2_i(),this.moveArea_i(),this.titleDisplay_i(),this.closeButton_i(),this.tabBar_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0xffffff;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.top = 100;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 100;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.style = "cd_label";
		t.left = 30;
		t.size = 20;
		t.top = 20;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.right = 20;
		t.top = 20;
		t.skinName = PhotoManagerPanelSkin$Skin23;
		return t;
	};
	_proto.tabBar_i = function () {
		var t = new eui.TabBar();
		this.tabBar = t;
		t.bottom = -1;
		t.left = 30;
		t.itemRendererSkinName = PhotoManagerPanelSkin$Skin24;
		return t;
	};
	_proto.viewStack_i = function () {
		var t = new eui.ViewStack();
		this.viewStack = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 101;
		return t;
	};
	return PhotoManagerPanelSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PreviewViewSkin.exml'] = window.app.PreviewViewSkin = (function (_super) {
	__extends(PreviewViewSkin, _super);
	function PreviewViewSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","dataGroup","listModeGroup","imageModeGroup","viewStack","closeButton","currModeDisplay","topGroup","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
		this._Group1_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("_Group1","overviewGroup",2,"viewStack")
				])
		];
	}
	var _proto = PreviewViewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this.backgroundRect_i(),this.viewStack_i(),this.topGroup_i()];
		return t;
	};
	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xf2f2f2;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.height = 90;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.width = 200;
		t.elementsContent = [this._Rect1_i(),this._Label1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.ellipseHeight = 25;
		t.ellipseWidth = 25;
		t.fillAlpha = 0.4;
		t.fillColor = 0x000000;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0xdddddd;
		t.strokeWeight = 1;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.horizontalCenter = 0;
		t.size = 14;
		t.text = "正在生成预览图...";
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		return t;
	};
	_proto.viewStack_i = function () {
		var t = new eui.ViewStack();
		this.viewStack = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 60;
		t.elementsContent = [this.listModeGroup_i(),this.imageModeGroup_i()];
		return t;
	};
	_proto.listModeGroup_i = function () {
		var t = new eui.Group();
		this.listModeGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Scroller1_i()];
		return t;
	};
	_proto._Scroller1_i = function () {
		var t = new eui.Scroller();
		t.bottom = 20;
		t.bounces = false;
		t.left = 0;
		t.right = 0;
		t.scrollPolicyH = "off";
		t.top = 30;
		t.viewport = this.dataGroup_i();
		return t;
	};
	_proto.dataGroup_i = function () {
		var t = new eui.DataGroup();
		this.dataGroup = t;
		t.useVirtualLayout = true;
		t.layout = this._VerticalLayout1_i();
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 30;
		t.horizontalAlign = "center";
		t.verticalAlign = "top";
		return t;
	};
	_proto.imageModeGroup_i = function () {
		var t = new eui.Group();
		this.imageModeGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect2_i(),this.closeButton_i(),this.currModeDisplay_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xffffff;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.height = 35;
		t.label = "返回";
		t.left = 20;
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto.currModeDisplay_i = function () {
		var t = new eui.Label();
		this.currModeDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.horizontalCenter = 0;
		t.size = 16;
		t.text = "";
		t.verticalCenter = 0;
		return t;
	};
	return PreviewViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PrinterOverviewPreviewSkin.exml'] = window.app.PrinterOverviewPreviewSkin = (function (_super) {
	__extends(PrinterOverviewPreviewSkin, _super);
	var PrinterOverviewPreviewSkin$Skin25 = 	(function (_super) {
		__extends(PrinterOverviewPreviewSkin$Skin25, _super);
		function PrinterOverviewPreviewSkin$Skin25() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group1","alpha",0.45)
					])
			];
		}
		var _proto = PrinterOverviewPreviewSkin$Skin25.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			this._Group1 = t;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_tip_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 12;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewPreviewSkin$Skin25;
	})(eui.Skin);

	function PrinterOverviewPreviewSkin() {
		_super.call(this);
		this.skinParts = ["spInfoDisplay","guideButton","topGroup","goodsCoverImage","goodsNameDisplay","sizeItemDisplay","worksNameDisplay","leftGroup","pageListGroup","rightGroup","mainGroup","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("batching",
				[
				])
		];
	}
	var _proto = PrinterOverviewPreviewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this._Rect1_i(),this._Rect2_i(),this.topGroup_i(),this.mainGroup_i(),this._Image1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0x666666;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0x444444;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this.spInfoDisplay_i(),this.guideButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto.spInfoDisplay_i = function () {
		var t = new eui.Label();
		this.spInfoDisplay = t;
		t.style = "cd_label";
		t.left = 25;
		t.size = 20;
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		return t;
	};
	_proto.guideButton_i = function () {
		var t = new eui.Button();
		this.guideButton = t;
		t.label = "使用教程";
		t.right = 25;
		t.verticalCenter = 0;
		t.skinName = PrinterOverviewPreviewSkin$Skin25;
		return t;
	};
	_proto.mainGroup_i = function () {
		var t = new eui.Group();
		this.mainGroup = t;
		t.bottom = 80;
		t.left = 10;
		t.right = 10;
		t.scrollEnabled = true;
		t.top = 70;
		t.elementsContent = [this._Rect3_i(),this._Rect4_i(),this._Rect5_i(),this._Rect6_i(),this.leftGroup_i(),this.rightGroup_i()];
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect4_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xC9C9C9;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.top = 50;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect5_i = function () {
		var t = new eui.Rect();
		t.bottom = 50;
		t.fillColor = 0xC9C9C9;
		t.height = 1;
		t.left = 280;
		t.right = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect6_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xC9C9C9;
		t.left = 280;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 1;
		return t;
	};
	_proto.leftGroup_i = function () {
		var t = new eui.Group();
		this.leftGroup = t;
		t.percentHeight = 100;
		t.width = 280;
		t.elementsContent = [this._Label1_i(),this._Group5_i()];
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.left = 0;
		t.size = 20;
		t.text = "商品信息";
		t.textAlign = "center";
		t.top = 15;
		t.width = 280;
		return t;
	};
	_proto._Group5_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.top = 70;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Group1_i(),this._Group2_i(),this._Group3_i(),this._Group4_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 15;
		t.horizontalAlign = "left";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 230;
		t.width = 230;
		t.elementsContent = [this._Rect7_i(),this.goodsCoverImage_i()];
		return t;
	};
	_proto._Rect7_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0x999999;
		t.strokeWeight = 1.5;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.goodsCoverImage_i = function () {
		var t = new eui.Image();
		this.goodsCoverImage = t;
		t.height = 220;
		t.horizontalCenter = 0;
		t.smoothing = true;
		t.verticalCenter = 0;
		t.width = 220;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Label2_i(),this.goodsNameDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "商品名：";
		t.width = 70;
		return t;
	};
	_proto.goodsNameDisplay_i = function () {
		var t = new eui.Label();
		this.goodsNameDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.size = 14;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout2_i();
		t.elementsContent = [this._Label3_i(),this.sizeItemDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label3_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "冲印规格：";
		t.width = 70;
		return t;
	};
	_proto.sizeItemDisplay_i = function () {
		var t = new eui.Label();
		this.sizeItemDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.size = 14;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout3_i();
		t.elementsContent = [this._Label4_i(),this.worksNameDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout3_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label4_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "作品名：";
		t.width = 70;
		return t;
	};
	_proto.worksNameDisplay_i = function () {
		var t = new eui.Label();
		this.worksNameDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		return t;
	};
	_proto.rightGroup_i = function () {
		var t = new eui.Group();
		this.rightGroup = t;
		t.percentHeight = 100;
		t.left = 281;
		t.right = 0;
		t.elementsContent = [this.pageListGroup_i()];
		return t;
	};
	_proto.pageListGroup_i = function () {
		var t = new eui.Group();
		this.pageListGroup = t;
		t.bottom = 51;
		t.left = 0;
		t.right = 0;
		t.scrollEnabled = true;
		t.top = 51;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.bottom = 22;
		t.horizontalCenter = 0;
		t.source = "img_printing_step_png";
		t.touchEnabled = false;
		return t;
	};
	return PrinterOverviewPreviewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/PrinterOverviewSkin.exml'] = window.app.PrinterOverviewSkin = (function (_super) {
	__extends(PrinterOverviewSkin, _super);
	var PrinterOverviewSkin$Skin26 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin26, _super);
		function PrinterOverviewSkin$Skin26() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group1","alpha",0.45)
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin26.prototype;

		_proto._Group1_i = function () {
			var t = new eui.Group();
			this._Group1 = t;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_tip_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 12;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin26;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin27 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin27, _super);
		function PrinterOverviewSkin$Skin27() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin27.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.smoothing = true;
			t.source = "btn_edit_png";
			return t;
		};
		return PrinterOverviewSkin$Skin27;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin28 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin28, _super);
		function PrinterOverviewSkin$Skin28() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Rect1_i(),this._Group1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",0.7)
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin28.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.bottom = 0;
			t.ellipseHeight = 10;
			t.ellipseWidth = 10;
			t.fillColor = 0xFC344D;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.gap = 5;
			t.horizontalAlign = "center";
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 25;
			t.smoothing = true;
			t.source = "btn_pho_switch_png";
			t.width = 25;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 18;
			t.textAlign = "left";
			t.textColor = 0xFFFFFF;
			t.verticalAlign = "middle";
			return t;
		};
		return PrinterOverviewSkin$Skin28;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin29 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin29, _super);
		function PrinterOverviewSkin$Skin29() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin29.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_pho_cut_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin29;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin30 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin30, _super);
		function PrinterOverviewSkin$Skin30() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin30.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_pho_white_space_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin30;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin31 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin31, _super);
		function PrinterOverviewSkin$Skin31() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin31.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "btn_pho_delete_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin31;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin32 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin32, _super);
		function PrinterOverviewSkin$Skin32() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group2","alpha",0.45)
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin32.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			this._Group2 = t;
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xb5b5b5;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin32;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin33 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin33, _super);
		function PrinterOverviewSkin$Skin33() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin33.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_batch_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin33;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin34 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin34, _super);
		function PrinterOverviewSkin$Skin34() {
			_super.call(this);
			this.skinParts = ["thumb","labelDisplay"];
			
			this.elementsContent = [this._Rect1_i(),this.thumb_i(),this.labelDisplay_i()];
		}
		var _proto = PrinterOverviewSkin$Skin34.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.fillColor = 0XF0F0F0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			return t;
		};
		_proto.thumb_i = function () {
			var t = new eui.Rect();
			this.thumb = t;
			t.bottom = 0;
			t.fillColor = 0x009900;
			t.left = 1;
			t.right = 0;
			t.top = 0;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.horizontalCenter = 0;
			t.size = 12;
			t.textAlign = "center";
			t.textColor = 0xffffff;
			t.verticalAlign = "middle";
			t.verticalCenter = 0;
			return t;
		};
		return PrinterOverviewSkin$Skin34;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin35 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin35, _super);
		function PrinterOverviewSkin$Skin35() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin35.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "btn_add_cart_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin35;
	})(eui.Skin);

	var PrinterOverviewSkin$Skin36 = 	(function (_super) {
		__extends(PrinterOverviewSkin$Skin36, _super);
		function PrinterOverviewSkin$Skin36() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group2","alpha",0.45)
					])
			];
		}
		var _proto = PrinterOverviewSkin$Skin36.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			this._Group2 = t;
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0x45b3f0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return PrinterOverviewSkin$Skin36;
	})(eui.Skin);

	function PrinterOverviewSkin() {
		_super.call(this);
		this.skinParts = ["spInfoDisplay","guideButton","topGroup","goodsCoverImage","goodsNameDisplay","sizeItemDisplay","worksNameDisplay","modifyWorksNameButton","uploadButton","uploadTipDisplay","leftGroup","selectAllCheckBox","batchCutButton","batchWhiteSpaceButton","batchDeleteButton","backButton","batchControlGroup","batchButton","mainControlGroup","pageListGroup","uploadProgressBar","uploadingInfoDisplay","uploadingInfoGroup","addToCartButton","submitButton","bottomControlGroup","rightGroup","mainGroup","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("batching",
				[
					new eui.SetProperty("batchControlGroup","visible",true),
					new eui.SetProperty("mainControlGroup","visible",false),
					new eui.SetProperty("mainControlGroup","includeInLayout",false)
				])
		];
	}
	var _proto = PrinterOverviewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this._Rect1_i(),this._Rect2_i(),this.topGroup_i(),this.mainGroup_i(),this._Image1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0x666666;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0x444444;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this.spInfoDisplay_i(),this.guideButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto.spInfoDisplay_i = function () {
		var t = new eui.Label();
		this.spInfoDisplay = t;
		t.style = "cd_label";
		t.left = 25;
		t.size = 20;
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		return t;
	};
	_proto.guideButton_i = function () {
		var t = new eui.Button();
		this.guideButton = t;
		t.label = "使用教程";
		t.right = 25;
		t.verticalCenter = 0;
		t.skinName = PrinterOverviewSkin$Skin26;
		return t;
	};
	_proto.mainGroup_i = function () {
		var t = new eui.Group();
		this.mainGroup = t;
		t.bottom = 80;
		t.left = 10;
		t.right = 10;
		t.scrollEnabled = true;
		t.top = 70;
		t.elementsContent = [this._Rect3_i(),this._Rect4_i(),this._Rect5_i(),this._Rect6_i(),this.leftGroup_i(),this.rightGroup_i()];
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect4_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xC9C9C9;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.top = 50;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect5_i = function () {
		var t = new eui.Rect();
		t.bottom = 50;
		t.fillColor = 0xC9C9C9;
		t.height = 1;
		t.left = 280;
		t.right = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Rect6_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xC9C9C9;
		t.left = 280;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 1;
		return t;
	};
	_proto.leftGroup_i = function () {
		var t = new eui.Group();
		this.leftGroup = t;
		t.percentHeight = 100;
		t.width = 280;
		t.elementsContent = [this._Label1_i(),this._Group6_i(),this.uploadButton_i(),this.uploadTipDisplay_i()];
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.left = 0;
		t.size = 20;
		t.text = "商品信息";
		t.textAlign = "center";
		t.top = 15;
		t.width = 280;
		return t;
	};
	_proto._Group6_i = function () {
		var t = new eui.Group();
		t.horizontalCenter = 0;
		t.top = 70;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Group1_i(),this._Group2_i(),this._Group3_i(),this._Group5_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 15;
		t.horizontalAlign = "left";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.height = 230;
		t.width = 230;
		t.elementsContent = [this._Rect7_i(),this.goodsCoverImage_i()];
		return t;
	};
	_proto._Rect7_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0x999999;
		t.strokeWeight = 1.5;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.goodsCoverImage_i = function () {
		var t = new eui.Image();
		this.goodsCoverImage = t;
		t.height = 220;
		t.horizontalCenter = 0;
		t.smoothing = true;
		t.verticalCenter = 0;
		t.width = 220;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Label2_i(),this.goodsNameDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "商品名：";
		t.width = 70;
		return t;
	};
	_proto.goodsNameDisplay_i = function () {
		var t = new eui.Label();
		this.goodsNameDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.size = 14;
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout2_i();
		t.elementsContent = [this._Label3_i(),this.sizeItemDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label3_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "冲印规格：";
		t.width = 70;
		return t;
	};
	_proto.sizeItemDisplay_i = function () {
		var t = new eui.Label();
		this.sizeItemDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		t.size = 14;
		return t;
	};
	_proto._Group5_i = function () {
		var t = new eui.Group();
		t.layout = this._HorizontalLayout3_i();
		t.elementsContent = [this._Label4_i(),this.worksNameDisplay_i(),this._Group4_i(),this.modifyWorksNameButton_i()];
		return t;
	};
	_proto._HorizontalLayout3_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 0;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label4_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "作品名：";
		t.width = 70;
		return t;
	};
	_proto.worksNameDisplay_i = function () {
		var t = new eui.Label();
		this.worksNameDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		t.height = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 5;
		return t;
	};
	_proto.modifyWorksNameButton_i = function () {
		var t = new eui.Button();
		this.modifyWorksNameButton = t;
		t.skinName = PrinterOverviewSkin$Skin27;
		return t;
	};
	_proto.uploadButton_i = function () {
		var t = new eui.Button();
		this.uploadButton = t;
		t.bottom = 65;
		t.height = 52;
		t.horizontalCenter = 0;
		t.label = "添加照片";
		t.width = 240;
		t.skinName = PrinterOverviewSkin$Skin28;
		return t;
	};
	_proto.uploadTipDisplay_i = function () {
		var t = new eui.Label();
		this.uploadTipDisplay = t;
		t.style = "cd_label";
		t.bottom = 10;
		t.horizontalCenter = 0;
		t.text = "提示：一次只能上传100张照片";
		t.textAlign = "center";
		return t;
	};
	_proto.rightGroup_i = function () {
		var t = new eui.Group();
		this.rightGroup = t;
		t.percentHeight = 100;
		t.left = 281;
		t.right = 0;
		t.elementsContent = [this.batchControlGroup_i(),this.mainControlGroup_i(),this.pageListGroup_i(),this.bottomControlGroup_i()];
		return t;
	};
	_proto.batchControlGroup_i = function () {
		var t = new eui.Group();
		this.batchControlGroup = t;
		t.height = 50;
		t.left = 0;
		t.right = 0;
		t.visible = false;
		t.elementsContent = [this._Rect8_i(),this.selectAllCheckBox_i(),this._Rect9_i(),this._Group7_i(),this.backButton_i()];
		return t;
	};
	_proto._Rect8_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xF2F2F2;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.selectAllCheckBox_i = function () {
		var t = new eui.CheckBox();
		this.selectAllCheckBox = t;
		t.label = "全选";
		t.left = 20;
		t.verticalCenter = 0;
		return t;
	};
	_proto._Rect9_i = function () {
		var t = new eui.Rect();
		t.bottom = 15;
		t.fillColor = 0x4C4E51;
		t.left = 90;
		t.top = 15;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.width = 1;
		return t;
	};
	_proto._Group7_i = function () {
		var t = new eui.Group();
		t.left = 110;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout4_i();
		t.elementsContent = [this.batchCutButton_i(),this.batchWhiteSpaceButton_i(),this.batchDeleteButton_i()];
		return t;
	};
	_proto._HorizontalLayout4_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 20;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.batchCutButton_i = function () {
		var t = new eui.Button();
		this.batchCutButton = t;
		t.height = 35;
		t.label = "批量裁剪";
		t.width = 100;
		t.skinName = PrinterOverviewSkin$Skin29;
		return t;
	};
	_proto.batchWhiteSpaceButton_i = function () {
		var t = new eui.Button();
		this.batchWhiteSpaceButton = t;
		t.height = 35;
		t.label = "批量留白";
		t.width = 100;
		t.skinName = PrinterOverviewSkin$Skin30;
		return t;
	};
	_proto.batchDeleteButton_i = function () {
		var t = new eui.Button();
		this.batchDeleteButton = t;
		t.height = 35;
		t.label = "批量删除";
		t.width = 100;
		t.skinName = PrinterOverviewSkin$Skin31;
		return t;
	};
	_proto.backButton_i = function () {
		var t = new eui.Button();
		this.backButton = t;
		t.height = 35;
		t.label = "返回列表";
		t.right = 20;
		t.verticalCenter = 0;
		t.width = 100;
		t.skinName = PrinterOverviewSkin$Skin32;
		return t;
	};
	_proto.mainControlGroup_i = function () {
		var t = new eui.Group();
		this.mainControlGroup = t;
		t.height = 50;
		t.left = 0;
		t.right = 0;
		t.elementsContent = [this.batchButton_i()];
		return t;
	};
	_proto.batchButton_i = function () {
		var t = new eui.Button();
		this.batchButton = t;
		t.height = 35;
		t.label = "批量处理";
		t.left = 20;
		t.verticalCenter = 0;
		t.width = 120;
		t.skinName = PrinterOverviewSkin$Skin33;
		return t;
	};
	_proto.pageListGroup_i = function () {
		var t = new eui.Group();
		this.pageListGroup = t;
		t.bottom = 51;
		t.left = 0;
		t.right = 0;
		t.scrollEnabled = true;
		t.top = 51;
		return t;
	};
	_proto.bottomControlGroup_i = function () {
		var t = new eui.Group();
		this.bottomControlGroup = t;
		t.bottom = 0;
		t.height = 50;
		t.left = 0;
		t.right = 0;
		t.elementsContent = [this.uploadingInfoGroup_i(),this._Group8_i()];
		return t;
	};
	_proto.uploadingInfoGroup_i = function () {
		var t = new eui.Group();
		this.uploadingInfoGroup = t;
		t.includeInLayout = false;
		t.left = 20;
		t.verticalCenter = 0;
		t.visible = false;
		t.layout = this._HorizontalLayout5_i();
		t.elementsContent = [this.uploadProgressBar_i(),this.uploadingInfoDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout5_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		t.horizontalAlign = "left";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.uploadProgressBar_i = function () {
		var t = new eui.ProgressBar();
		this.uploadProgressBar = t;
		t.height = 15;
		t.maximum = 100;
		t.minimum = 0;
		t.width = 120;
		t.skinName = PrinterOverviewSkin$Skin34;
		return t;
	};
	_proto.uploadingInfoDisplay_i = function () {
		var t = new eui.Label();
		this.uploadingInfoDisplay = t;
		t.style = "cd_label";
		t.size = 12;
		return t;
	};
	_proto._Group8_i = function () {
		var t = new eui.Group();
		t.right = 20;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout6_i();
		t.elementsContent = [this.addToCartButton_i(),this.submitButton_i()];
		return t;
	};
	_proto._HorizontalLayout6_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 15;
		t.verticalAlign = "middle";
		return t;
	};
	_proto.addToCartButton_i = function () {
		var t = new eui.Button();
		this.addToCartButton = t;
		t.height = 35;
		t.label = "加入购物车";
		t.width = 140;
		t.skinName = PrinterOverviewSkin$Skin35;
		return t;
	};
	_proto.submitButton_i = function () {
		var t = new eui.Button();
		this.submitButton = t;
		t.height = 35;
		t.label = "立即购买";
		t.width = 110;
		t.skinName = PrinterOverviewSkin$Skin36;
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.bottom = 22;
		t.horizontalCenter = 0;
		t.source = "img_printing_step_png";
		t.touchEnabled = false;
		return t;
	};
	return PrinterOverviewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/QuickFillButtonSkin.exml'] = window.app.QuickFillButtonSkin = (function (_super) {
	__extends(QuickFillButtonSkin, _super);
	function QuickFillButtonSkin() {
		_super.call(this);
		this.skinParts = ["iconDisplay","labelDisplay"];
		
		this.elementsContent = [this._Rect1_i(),this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Rect1","fillAlpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
				])
		];
	}
	var _proto = QuickFillButtonSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		this._Rect1 = t;
		t.bottom = 0;
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0x67C3F6;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 10;
		t.left = 15;
		t.right = 15;
		t.top = 10;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.iconDisplay_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 5;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.iconDisplay_i = function () {
		var t = new eui.Image();
		this.iconDisplay = t;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.verticalAlign = "middle";
		return t;
	};
	return QuickFillButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/QuickFillPopupSkin.exml'] = window.app.QuickFillPopupSkin = (function (_super) {
	__extends(QuickFillPopupSkin, _super);
	function QuickFillPopupSkin() {
		_super.call(this);
		this.skinParts = ["fillModeRadioButtonGroup","titleDisplay","moveArea","submitButton","cancelButton","mainContent","contentGroup","contentGroups"];
		
		this.width = 300;
		this.fillModeRadioButtonGroup_i();
		this.elementsContent = [this.contentGroups_i()];
		
		eui.Binding.$bindProperties(this, ["fillModeRadioButtonGroup"],[0],this._RadioButton1,"group");
		eui.Binding.$bindProperties(this, ["fillModeRadioButtonGroup"],[0],this._RadioButton2,"group");
	}
	var _proto = QuickFillPopupSkin.prototype;

	_proto.fillModeRadioButtonGroup_i = function () {
		var t = new eui.RadioButtonGroup();
		this.fillModeRadioButtonGroup = t;
		return t;
	};
	_proto.contentGroups_i = function () {
		var t = new eui.Group();
		this.contentGroups = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Rect1_i(),this.contentGroup_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.moveArea_i(),this.mainContent_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 40;
		t.percentWidth = 100;
		t.elementsContent = [this.titleDisplay_i()];
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.left = 17;
		t.size = 18;
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		t.wordWrap = false;
		return t;
	};
	_proto.mainContent_i = function () {
		var t = new eui.Group();
		this.mainContent = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this._Group1_i(),this._Group2_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 20;
		t.horizontalAlign = "center";
		t.paddingBottom = 15;
		t.paddingLeft = 20;
		t.paddingRight = 20;
		t.paddingTop = 15;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.layout = this._VerticalLayout3_i();
		t.elementsContent = [this._RadioButton1_i(),this._RadioButton2_i()];
		return t;
	};
	_proto._VerticalLayout3_i = function () {
		var t = new eui.VerticalLayout();
		t.horizontalAlign = "left";
		t.verticalAlign = "top";
		return t;
	};
	_proto._RadioButton1_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton1 = t;
		t.label = "用全部照片重新填充所有页面相框";
		t.value = "0";
		return t;
	};
	_proto._RadioButton2_i = function () {
		var t = new eui.RadioButton();
		this._RadioButton2 = t;
		t.label = "用未使用的照片填充所有页面空相框";
		t.selected = true;
		t.value = "1";
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.submitButton_i(),this.cancelButton_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.horizontalAlign = "right";
		return t;
	};
	_proto.submitButton_i = function () {
		var t = new eui.Button();
		this.submitButton = t;
		t.height = 35;
		t.label = "提交";
		t.skinName = "app.OKButtonSkin";
		t.width = 70;
		return t;
	};
	_proto.cancelButton_i = function () {
		var t = new eui.Button();
		this.cancelButton = t;
		t.height = 35;
		t.label = "取消";
		t.width = 70;
		return t;
	};
	return QuickFillPopupSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/SaveWorksConfirmPopupSkin.exml'] = window.app.SaveWorksConfirmPopupSkin = (function (_super) {
	__extends(SaveWorksConfirmPopupSkin, _super);
	var SaveWorksConfirmPopupSkin$Skin37 = 	(function (_super) {
		__extends(SaveWorksConfirmPopupSkin$Skin37, _super);
		function SaveWorksConfirmPopupSkin$Skin37() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = SaveWorksConfirmPopupSkin$Skin37.prototype;

		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.source = "btn_win_close_png";
			return t;
		};
		return SaveWorksConfirmPopupSkin$Skin37;
	})(eui.Skin);

	function SaveWorksConfirmPopupSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","moveArea","titleDisplay","closeButton","topGroup","orderNumInput","orderNumGroup","workNameInput","workNameGroup","userNameInput","userNameGroup","buyQuantityInput","quantityGroup","remarkInput","remarkGroup","fullNameInput","fullNameGroup","telNumInput","telNumGroup","addressDetailInput","locationSelectionBar","addressGroup","confirmButton","cancelButton","contentGroup"];
		
		this.elementsContent = [this.backgroundRect_i(),this.topGroup_i(),this.contentGroup_i()];
		this.orderNumInput_i();
		
		this.orderNumGroup_i();
		
		this.userNameInput_i();
		
		this.userNameGroup_i();
		
		this.remarkInput_i();
		
		this.remarkGroup_i();
		
		this.fullNameInput_i();
		
		this.fullNameGroup_i();
		
		this.telNumInput_i();
		
		this.telNumGroup_i();
		
		this.addressDetailInput_i();
		
		this.locationSelectionBar_i();
		
		this.addressGroup_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("fromTaobao",
				[
					new eui.AddItems("orderNumInput","orderNumGroup",2,"_Label3"),
					new eui.AddItems("orderNumGroup","contentGroup",2,"workNameGroup"),
					new eui.AddItems("userNameInput","userNameGroup",2,"_Label7"),
					new eui.AddItems("userNameGroup","contentGroup",2,"quantityGroup"),
					new eui.AddItems("telNumInput","telNumGroup",2,"_Label15"),
					new eui.AddItems("telNumGroup","contentGroup",2,"_Group3")
				])
			,
			new eui.State ("fromJD",
				[
					new eui.AddItems("orderNumInput","orderNumGroup",2,"_Label3"),
					new eui.AddItems("orderNumGroup","contentGroup",2,"workNameGroup"),
					new eui.AddItems("userNameInput","userNameGroup",2,"_Label7"),
					new eui.AddItems("userNameGroup","contentGroup",2,"quantityGroup"),
					new eui.AddItems("telNumInput","telNumGroup",2,"_Label15"),
					new eui.AddItems("telNumGroup","contentGroup",2,"_Group3"),
					new eui.SetProperty("_Label6","text","会员名称"),
					new eui.SetProperty("userNameInput","prompt","输入会员名称")
				])
			,
			new eui.State ("fromWeiShang",
				[
					new eui.AddItems("userNameInput","userNameGroup",2,"_Label7"),
					new eui.AddItems("userNameGroup","contentGroup",2,"quantityGroup"),
					new eui.AddItems("remarkInput","remarkGroup",2,"_Label11"),
					new eui.AddItems("remarkGroup","contentGroup",2,"_Group3"),
					new eui.AddItems("fullNameInput","fullNameGroup",2,"_Label13"),
					new eui.AddItems("fullNameGroup","contentGroup",2,"_Group3"),
					new eui.AddItems("telNumInput","telNumGroup",2,"_Label15"),
					new eui.AddItems("telNumGroup","contentGroup",2,"_Group3"),
					new eui.AddItems("addressDetailInput","_Group1",0,""),
					new eui.AddItems("locationSelectionBar","_Group1",1,""),
					new eui.AddItems("addressGroup","contentGroup",2,"_Group3"),
					new eui.SetProperty("_Label6","text","用户昵称"),
					new eui.SetProperty("_Label7","visible",false)
				])
			,
			new eui.State ("fromExShop",
				[
					new eui.SetProperty("_Label1","text","请填写和确认作品名称，以便在作品列表中方便查找")
				])
		];
		
		eui.Binding.$bindProperties(this, ["hostComponent.isSubmit"],[0],this._Label3,"visible");
		eui.Binding.$bindProperties(this, ["hostComponent.isSubmit"],[0],this._Label9,"visible");
		eui.Binding.$bindProperties(this, ["hostComponent.isSubmit"],[0],this._Label13,"visible");
		eui.Binding.$bindProperties(this, ["hostComponent.isSubmit"],[0],this._Label15,"visible");
		eui.Binding.$bindProperties(this, ["hostComponent.isSubmit"],[0],this._Label17,"visible");
	}
	var _proto = SaveWorksConfirmPopupSkin.prototype;

	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 55;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect1_i(),this.moveArea_i(),this.titleDisplay_i(),this.closeButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 0;
		t.right = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 100;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.style = "cd_label";
		t.left = 30;
		t.size = 20;
		t.textColor = 0x000000;
		t.top = 20;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.right = 20;
		t.top = 20;
		t.skinName = SaveWorksConfirmPopupSkin$Skin37;
		return t;
	};
	_proto.contentGroup_i = function () {
		var t = new eui.Group();
		this.contentGroup = t;
		t.bottom = 0;
		t.left = 0;
		t.minHeight = 0;
		t.minWidth = 450;
		t.right = 0;
		t.top = 56;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Label1_i(),this.workNameGroup_i(),this.quantityGroup_i(),this._Group3_i(),this._Group4_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 15;
		t.horizontalAlign = "left";
		t.paddingBottom = 20;
		t.paddingLeft = 40;
		t.paddingRight = 40;
		t.paddingTop = 20;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		this._Label1 = t;
		t.style = "cd_label";
		t.size = 14;
		t.text = "请填写以下信息，以便商家确认作品！";
		t.textAlign = "center";
		t.textColor = 0xA2A2A2;
		t.percentWidth = 100;
		return t;
	};
	_proto.orderNumGroup_i = function () {
		var t = new eui.Group();
		this.orderNumGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Label2_i(),this._Label3_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "订单编号：";
		t.width = 70;
		return t;
	};
	_proto.orderNumInput_i = function () {
		var t = new eui.TextInput();
		this.orderNumInput = t;
		t.height = 28;
		t.maxChars = 30;
		t.restrict = "0-9";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label3_i = function () {
		var t = new eui.Label();
		this._Label3 = t;
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.workNameGroup_i = function () {
		var t = new eui.Group();
		this.workNameGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout2_i();
		t.elementsContent = [this._Label4_i(),this.workNameInput_i(),this._Label5_i()];
		return t;
	};
	_proto._HorizontalLayout2_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label4_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "作品名称：";
		t.width = 70;
		return t;
	};
	_proto.workNameInput_i = function () {
		var t = new eui.TextInput();
		this.workNameInput = t;
		t.height = 28;
		t.maxChars = 50;
		t.prompt = "请为您的作品命名";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label5_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.userNameGroup_i = function () {
		var t = new eui.Group();
		this.userNameGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout3_i();
		t.elementsContent = [this._Label6_i(),this._Label7_i()];
		return t;
	};
	_proto._HorizontalLayout3_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label6_i = function () {
		var t = new eui.Label();
		this._Label6 = t;
		t.style = "cd_label";
		t.size = 14;
		t.text = "旺旺昵称：";
		t.width = 70;
		return t;
	};
	_proto.userNameInput_i = function () {
		var t = new eui.TextInput();
		this.userNameInput = t;
		t.height = 28;
		t.maxChars = 50;
		t.prompt = "输入付款的旺旺昵称";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label7_i = function () {
		var t = new eui.Label();
		this._Label7 = t;
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.quantityGroup_i = function () {
		var t = new eui.Group();
		this.quantityGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout4_i();
		t.elementsContent = [this._Label8_i(),this.buyQuantityInput_i(),this._Label9_i()];
		return t;
	};
	_proto._HorizontalLayout4_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label8_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "购买数量：";
		t.width = 70;
		return t;
	};
	_proto.buyQuantityInput_i = function () {
		var t = new eui.TextInput();
		this.buyQuantityInput = t;
		t.height = 28;
		t.maxChars = 5;
		t.prompt = "输入需购买的份数";
		t.restrict = "0-9";
		t.text = "1";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label9_i = function () {
		var t = new eui.Label();
		this._Label9 = t;
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.remarkGroup_i = function () {
		var t = new eui.Group();
		this.remarkGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout5_i();
		t.elementsContent = [this._Label10_i(),this._Label11_i()];
		return t;
	};
	_proto._HorizontalLayout5_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label10_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "备注：";
		t.width = 70;
		return t;
	};
	_proto.remarkInput_i = function () {
		var t = new eui.TextInput();
		this.remarkInput = t;
		t.height = 60;
		t.maxChars = 500;
		t.prompt = "输入所需的备注信息";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label11_i = function () {
		var t = new eui.Label();
		this._Label11 = t;
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		t.visible = false;
		return t;
	};
	_proto.fullNameGroup_i = function () {
		var t = new eui.Group();
		this.fullNameGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout6_i();
		t.elementsContent = [this._Label12_i(),this._Label13_i()];
		return t;
	};
	_proto._HorizontalLayout6_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label12_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "收货人：";
		t.width = 70;
		return t;
	};
	_proto.fullNameInput_i = function () {
		var t = new eui.TextInput();
		this.fullNameInput = t;
		t.height = 28;
		t.maxChars = 10;
		t.prompt = "输入收货人姓名";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label13_i = function () {
		var t = new eui.Label();
		this._Label13 = t;
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.telNumGroup_i = function () {
		var t = new eui.Group();
		this.telNumGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout7_i();
		t.elementsContent = [this._Label14_i(),this._Label15_i()];
		return t;
	};
	_proto._HorizontalLayout7_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label14_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "手机号码：";
		t.width = 70;
		return t;
	};
	_proto.telNumInput_i = function () {
		var t = new eui.TextInput();
		this.telNumInput = t;
		t.height = 28;
		t.maxChars = 15;
		t.prompt = "输入11位手机号码";
		t.percentWidth = 100;
		return t;
	};
	_proto._Label15_i = function () {
		var t = new eui.Label();
		this._Label15 = t;
		t.style = "cd_label";
		t.size = 16;
		t.text = "*";
		t.textColor = 0xFF0000;
		return t;
	};
	_proto.addressGroup_i = function () {
		var t = new eui.Group();
		this.addressGroup = t;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout8_i();
		t.elementsContent = [this._Label16_i(),this._Group1_i(),this._Group2_i()];
		return t;
	};
	_proto._HorizontalLayout8_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Label16_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.size = 14;
		t.text = "收货地址：";
		t.touchEnabled = false;
		t.width = 70;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.percentWidth = 100;
		t.elementsContent = [];
		return t;
	};
	_proto.addressDetailInput_i = function () {
		var t = new eui.TextInput();
		this.addressDetailInput = t;
		t.height = 40;
		t.maxChars = 150;
		t.prompt = "输入详细地址";
		t.top = 40;
		t.percentWidth = 100;
		return t;
	};
	_proto.locationSelectionBar_i = function () {
		var t = new components.LocationSelectionBar();
		this.locationSelectionBar = t;
		t.height = 40;
		t.percentWidth = 100;
		return t;
	};
	_proto._Group2_i = function () {
		var t = new eui.Group();
		t.layout = this._VerticalLayout2_i();
		t.elementsContent = [this._Label17_i()];
		return t;
	};
	_proto._VerticalLayout2_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 5;
		return t;
	};
	_proto._Label17_i = function () {
		var t = new eui.Label();
		this._Label17 = t;
		t.height = 40;
		t.size = 18;
		t.text = "*";
		t.textColor = 0xFF0000;
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Group3_i = function () {
		var t = new eui.Group();
		this._Group3 = t;
		t.height = 100;
		t.width = 1;
		return t;
	};
	_proto._Group4_i = function () {
		var t = new eui.Group();
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout9_i();
		t.elementsContent = [this.confirmButton_i(),this.cancelButton_i()];
		return t;
	};
	_proto._HorizontalLayout9_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 15;
		t.horizontalAlign = "right";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.confirmButton_i = function () {
		var t = new eui.Button();
		this.confirmButton = t;
		t.label = "确定提交";
		t.skinName = "app.OKButtonSkin";
		t.width = 80;
		return t;
	};
	_proto.cancelButton_i = function () {
		var t = new eui.Button();
		this.cancelButton = t;
		t.label = "取消";
		t.skinName = "app.CancelButtonSkin";
		t.width = 80;
		return t;
	};
	return SaveWorksConfirmPopupSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/SpecialElementSkin.exml'] = window.app.SpecialElementSkin = (function (_super) {
	__extends(SpecialElementSkin, _super);
	function SpecialElementSkin() {
		_super.call(this);
		this.skinParts = ["loadingDisplay","imageComp"];
		
		this.elementsContent = [this._Group1_i()];
		this.loadingDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("loading",
				[
					new eui.AddItems("loadingDisplay","_Group1",0,"")
				])
		];
	}
	var _proto = SpecialElementSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		this._Group1 = t;
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.elementsContent = [this.imageComp_i()];
		return t;
	};
	_proto.loadingDisplay_i = function () {
		var t = new eui.Label();
		this.loadingDisplay = t;
		t.horizontalCenter = 0;
		t.size = 12;
		t.text = "正在加载...";
		t.textColor = 0x000000;
		t.verticalCenter = 0;
		return t;
	};
	_proto.imageComp_i = function () {
		var t = new eui.Image();
		this.imageComp = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	return SpecialElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/StageOverviewSkin.exml'] = window.app.StageOverviewSkin = (function (_super) {
	__extends(StageOverviewSkin, _super);
	function StageOverviewSkin() {
		_super.call(this);
		this.skinParts = ["bottomLayerContainer","backgroundColorContainer","backgroundGroup","stageGroup","tipLayerContainer","bleedTipRect","pageBorderRect","designLayerContainer","topLayerContainer","transformHandlesLayerContainer"];
		
		this.elementsContent = [this.bottomLayerContainer_i(),this.designLayerContainer_i(),this.topLayerContainer_i(),this._Group1_i()];
	}
	var _proto = StageOverviewSkin.prototype;

	_proto.bottomLayerContainer_i = function () {
		var t = new eui.Group();
		this.bottomLayerContainer = t;
		return t;
	};
	_proto.designLayerContainer_i = function () {
		var t = new eui.Group();
		this.designLayerContainer = t;
		t.elementsContent = [this.backgroundColorContainer_i(),this.backgroundGroup_i(),this.stageGroup_i(),this.tipLayerContainer_i(),this.bleedTipRect_i(),this.pageBorderRect_i()];
		return t;
	};
	_proto.backgroundColorContainer_i = function () {
		var t = new eui.Group();
		this.backgroundColorContainer = t;
		t.bottom = 1;
		t.left = 1;
		t.right = 1;
		t.top = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.elementsContent = [this._Rect1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xFFFFFF;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		return t;
	};
	_proto.backgroundGroup_i = function () {
		var t = new eui.Group();
		this.backgroundGroup = t;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		return t;
	};
	_proto.tipLayerContainer_i = function () {
		var t = new eui.Group();
		this.tipLayerContainer = t;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.bleedTipRect_i = function () {
		var t = new eui.Rect();
		this.bleedTipRect = t;
		t.alpha = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.pageBorderRect_i = function () {
		var t = new eui.Rect();
		this.pageBorderRect = t;
		t.bottom = 1;
		t.fillAlpha = 0;
		t.left = 1;
		t.right = 1;
		t.strokeColor = 0x999999;
		t.top = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.topLayerContainer_i = function () {
		var t = new eui.Group();
		this.topLayerContainer = t;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.touchThrough = true;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.touchEnabled = false;
		t.touchThrough = true;
		t.elementsContent = [this.transformHandlesLayerContainer_i()];
		return t;
	};
	_proto.transformHandlesLayerContainer_i = function () {
		var t = new eui.Group();
		this.transformHandlesLayerContainer = t;
		t.touchThrough = true;
		return t;
	};
	return StageOverviewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/TextEditorViewSkin.exml'] = window.app.TextEditorViewSkin = (function (_super) {
	__extends(TextEditorViewSkin, _super);
	function TextEditorViewSkin() {
		_super.call(this);
		this.skinParts = ["backgroundRect","textArea","stageGroup","closeButton","submitButton","topGroup","overviewGroup"];
		
		this.elementsContent = [this.overviewGroup_i()];
	}
	var _proto = TextEditorViewSkin.prototype;

	_proto.overviewGroup_i = function () {
		var t = new eui.Group();
		this.overviewGroup = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.elementsContent = [this.backgroundRect_i(),this.stageGroup_i(),this.topGroup_i()];
		return t;
	};
	_proto.backgroundRect_i = function () {
		var t = new eui.Rect();
		this.backgroundRect = t;
		t.bottom = 0;
		t.fillColor = 0xf2f2f2;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.stageGroup_i = function () {
		var t = new eui.Group();
		this.stageGroup = t;
		t.bottom = 0;
		t.left = 25;
		t.right = 25;
		t.top = 90;
		t.elementsContent = [this._Rect1_i(),this.textArea_i(),this._Group1_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.fillColor = 0xffffff;
		t.height = 220;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0xA2A2A2;
		t.strokeWeight = 1;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.textArea_i = function () {
		var t = new eui.EditableText();
		this.textArea = t;
		t.height = 200;
		t.left = "10";
		t.lineSpacing = 10;
		t.multiline = true;
		t.right = "10";
		t.size = 14;
		t.textColor = 0x000000;
		t.top = "10";
		t.wordWrap = true;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.left = 0;
		t.top = 240;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this._Label1_i(),this._Label2_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 5;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "如出现生成的文字空白或丢失，则说明该字体不支持该字符，";
		return t;
	};
	_proto._Label2_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "请更换常用的字符或文字重新录入。";
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect2_i(),this.closeButton_i(),this._Label3_i(),this.submitButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xffffff;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.height = 35;
		t.label = "返回";
		t.left = 20;
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	_proto._Label3_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.horizontalCenter = 0;
		t.size = 16;
		t.text = "文本框编辑";
		t.verticalCenter = 0;
		return t;
	};
	_proto.submitButton_i = function () {
		var t = new eui.Button();
		this.submitButton = t;
		t.height = 35;
		t.label = "确定";
		t.right = 20;
		t.skinName = "app.OKButtonSkin";
		t.verticalCenter = 0;
		t.width = 70;
		return t;
	};
	return TextEditorViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/TextElementSkin.exml'] = window.app.TextElementSkin = (function (_super) {
	__extends(TextElementSkin, _super);
	function TextElementSkin() {
		_super.call(this);
		this.skinParts = ["editingRect","loadingTextComp","textComp"];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("editing",
				[
				])
			,
			new eui.State ("loading",
				[
					new eui.SetProperty("loadingTextComp","visible",true),
					new eui.SetProperty("loadingTextComp","includeInLayout",true),
					new eui.SetProperty("textComp","visible",false)
				])
		];
	}
	var _proto = TextElementSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.elementsContent = [this.editingRect_i(),this.loadingTextComp_i(),this.textComp_i()];
		return t;
	};
	_proto.editingRect_i = function () {
		var t = new eui.Rect();
		this.editingRect = t;
		t.bottom = 0;
		t.fillAlpha = 0.5;
		t.fillColor = 0xDDDDDD;
		t.left = 0;
		t.right = 0;
		t.strokeAlpha = 0.5;
		t.strokeColor = 0xdddddd;
		t.top = 0;
		t.visible = false;
		return t;
	};
	_proto.loadingTextComp_i = function () {
		var t = new eui.Label();
		this.loadingTextComp = t;
		t.includeInLayout = false;
		t.text = "正在加载....";
		t.visible = false;
		return t;
	};
	_proto.textComp_i = function () {
		var t = new eui.Label();
		this.textComp = t;
		t.percentHeight = 100;
		t.percentWidth = 100;
		return t;
	};
	return TextElementSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/app/UploadPhotoViewSkin.exml'] = window.app.UploadPhotoViewSkin = (function (_super) {
	__extends(UploadPhotoViewSkin, _super);
	var UploadPhotoViewSkin$Skin38 = 	(function (_super) {
		__extends(UploadPhotoViewSkin$Skin38, _super);
		function UploadPhotoViewSkin$Skin38() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
						new eui.SetProperty("_Group2","alpha",0.45)
					])
			];
		}
		var _proto = UploadPhotoViewSkin$Skin38.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			this._Group2 = t;
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.size = 16;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return UploadPhotoViewSkin$Skin38;
	})(eui.Skin);

	var UploadPhotoViewSkin$Skin39 = 	(function (_super) {
		__extends(UploadPhotoViewSkin$Skin39, _super);
		function UploadPhotoViewSkin$Skin39() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = UploadPhotoViewSkin$Skin39.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xb5b5b5;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "btn_trash_w_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return UploadPhotoViewSkin$Skin39;
	})(eui.Skin);

	var UploadPhotoViewSkin$Skin40 = 	(function (_super) {
		__extends(UploadPhotoViewSkin$Skin40, _super);
		function UploadPhotoViewSkin$Skin40() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = UploadPhotoViewSkin$Skin40.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "btn_pho_switch_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return UploadPhotoViewSkin$Skin40;
	})(eui.Skin);

	var UploadPhotoViewSkin$Skin41 = 	(function (_super) {
		__extends(UploadPhotoViewSkin$Skin41, _super);
		function UploadPhotoViewSkin$Skin41() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Group2_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = UploadPhotoViewSkin$Skin41.prototype;

		_proto._Group2_i = function () {
			var t = new eui.Group();
			t.bottom = 0;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.elementsContent = [this._Rect1_i(),this._Group1_i()];
			return t;
		};
		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillColor = 0xff5169;
			t.left = 0;
			t.right = 0;
			t.top = 0;
			t.touchChildren = false;
			t.touchEnabled = false;
			return t;
		};
		_proto._Group1_i = function () {
			var t = new eui.Group();
			t.horizontalCenter = 0;
			t.verticalCenter = 0;
			t.layout = this._HorizontalLayout1_i();
			t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
			return t;
		};
		_proto._HorizontalLayout1_i = function () {
			var t = new eui.HorizontalLayout();
			t.verticalAlign = "middle";
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.height = 18;
			t.smoothing = true;
			t.source = "icon_pho_mask_png";
			t.width = 18;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.size = 14;
			t.textColor = 0xFFFFFF;
			return t;
		};
		return UploadPhotoViewSkin$Skin41;
	})(eui.Skin);

	function UploadPhotoViewSkin() {
		_super.call(this);
		this.skinParts = ["addPhotosButton","addPhotosTipDisplay","normalGroup","selectAllCheckBox","deletePhotosButton","appendPhotosButton","topGroup","dataGroup","listScroller","photoProcessingDisplay","processingDisplayGroup","selectedInfoDisplay","photoInfoDisplay","importPhotosButton","bottomGroup","listGroup"];
		
		this.elementsContent = [this.listGroup_i()];
		this.normalGroup_i();
		
		this.states = [
			new eui.State ("normal",
				[
					new eui.AddItems("normalGroup","",2,"listGroup")
				])
			,
			new eui.State ("list",
				[
					new eui.SetProperty("listGroup","visible",true),
					new eui.SetProperty("listGroup","includeInLayout",true)
				])
		];
	}
	var _proto = UploadPhotoViewSkin.prototype;

	_proto.normalGroup_i = function () {
		var t = new eui.Group();
		this.normalGroup = t;
		t.horizontalCenter = 0;
		t.verticalCenter = 0;
		t.layout = this._VerticalLayout1_i();
		t.elementsContent = [this.addPhotosButton_i(),this.addPhotosTipDisplay_i()];
		return t;
	};
	_proto._VerticalLayout1_i = function () {
		var t = new eui.VerticalLayout();
		t.gap = 10;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.addPhotosButton_i = function () {
		var t = new eui.Button();
		this.addPhotosButton = t;
		t.height = 60;
		t.label = "本地添加";
		t.width = 185;
		t.skinName = UploadPhotoViewSkin$Skin38;
		return t;
	};
	_proto.addPhotosTipDisplay_i = function () {
		var t = new eui.Label();
		this.addPhotosTipDisplay = t;
		t.style = "cd_label";
		return t;
	};
	_proto.listGroup_i = function () {
		var t = new eui.Group();
		this.listGroup = t;
		t.bottom = 0;
		t.includeInLayout = false;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.visible = false;
		t.elementsContent = [this.topGroup_i(),this.listScroller_i(),this.processingDisplayGroup_i(),this.bottomGroup_i()];
		return t;
	};
	_proto.topGroup_i = function () {
		var t = new eui.Group();
		this.topGroup = t;
		t.height = 60;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Rect1_i(),this.selectAllCheckBox_i(),this.deletePhotosButton_i(),this.appendPhotosButton_i()];
		return t;
	};
	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xdedede;
		t.height = 1;
		t.left = 15;
		t.right = 15;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.selectAllCheckBox_i = function () {
		var t = new eui.CheckBox();
		this.selectAllCheckBox = t;
		t.label = "全选";
		t.left = 20;
		t.verticalCenter = 0;
		return t;
	};
	_proto.deletePhotosButton_i = function () {
		var t = new eui.Button();
		this.deletePhotosButton = t;
		t.height = 35;
		t.label = "删除照片";
		t.right = 145;
		t.verticalCenter = 0;
		t.width = 120;
		t.skinName = UploadPhotoViewSkin$Skin39;
		return t;
	};
	_proto.appendPhotosButton_i = function () {
		var t = new eui.Button();
		this.appendPhotosButton = t;
		t.height = 35;
		t.label = "添加照片";
		t.right = 15;
		t.verticalCenter = 0;
		t.width = 120;
		t.skinName = UploadPhotoViewSkin$Skin40;
		return t;
	};
	_proto.listScroller_i = function () {
		var t = new eui.Scroller();
		this.listScroller = t;
		t.bottom = 75;
		t.bounces = false;
		t.left = 20;
		t.right = 20;
		t.top = 80;
		t.viewport = this.dataGroup_i();
		return t;
	};
	_proto.dataGroup_i = function () {
		var t = new eui.DataGroup();
		this.dataGroup = t;
		t.useVirtualLayout = true;
		t.layout = this._TileLayout1_i();
		return t;
	};
	_proto._TileLayout1_i = function () {
		var t = new eui.TileLayout();
		t.columnWidth = 130;
		t.horizontalGap = 15;
		t.rowHeight = 130;
		t.verticalGap = 15;
		return t;
	};
	_proto.processingDisplayGroup_i = function () {
		var t = new eui.Group();
		this.processingDisplayGroup = t;
		t.bottom = 55;
		t.height = 40;
		t.includeInLayout = false;
		t.left = 0;
		t.right = 0;
		t.visible = false;
		t.elementsContent = [this._Rect2_i(),this.photoProcessingDisplay_i()];
		return t;
	};
	_proto._Rect2_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillAlpha = 0.45;
		t.fillColor = 0x000000;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto.photoProcessingDisplay_i = function () {
		var t = new eui.Label();
		this.photoProcessingDisplay = t;
		t.style = "cd_label";
		t.horizontalCenter = 0;
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		return t;
	};
	_proto.bottomGroup_i = function () {
		var t = new eui.Group();
		this.bottomGroup = t;
		t.bottom = 0;
		t.height = 55;
		t.left = 0;
		t.right = 0;
		t.layout = this._BasicLayout1_i();
		t.elementsContent = [this._Rect3_i(),this._Group1_i(),this.importPhotosButton_i()];
		return t;
	};
	_proto._BasicLayout1_i = function () {
		var t = new eui.BasicLayout();
		return t;
	};
	_proto._Rect3_i = function () {
		var t = new eui.Rect();
		t.bottom = 0;
		t.fillColor = 0xF3F3F3;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.touchChildren = false;
		t.touchEnabled = false;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.left = 15;
		t.verticalCenter = 0;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.selectedInfoDisplay_i(),this._Label1_i(),this.photoInfoDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 10;
		return t;
	};
	_proto.selectedInfoDisplay_i = function () {
		var t = new eui.Label();
		this.selectedInfoDisplay = t;
		t.style = "cd_label";
		t.bold = true;
		return t;
	};
	_proto._Label1_i = function () {
		var t = new eui.Label();
		t.style = "cd_label";
		t.text = "|";
		return t;
	};
	_proto.photoInfoDisplay_i = function () {
		var t = new eui.Label();
		this.photoInfoDisplay = t;
		t.style = "cd_label";
		return t;
	};
	_proto.importPhotosButton_i = function () {
		var t = new eui.Button();
		this.importPhotosButton = t;
		t.height = 35;
		t.label = "导入到设计区";
		t.right = 15;
		t.verticalCenter = 0;
		t.width = 150;
		t.skinName = UploadPhotoViewSkin$Skin41;
		return t;
	};
	return UploadPhotoViewSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ButtonSkin.exml'] = window.skins.ButtonSkin = (function (_super) {
	__extends(ButtonSkin, _super);
	function ButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Rect1_i(),this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Rect1","fillAlpha",1)
				])
			,
			new eui.State ("disabled",
				[
				])
		];
	}
	var _proto = ButtonSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		this._Rect1 = t;
		t.bottom = 0;
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillAlpha = 0;
		t.fillColor = 0xF2F2F2;
		t.left = 0;
		t.right = 0;
		t.strokeColor = 0x454545;
		t.strokeWeight = 1;
		t.top = 0;
		return t;
	};
	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.bottom = 7;
		t.left = 15;
		t.right = 15;
		t.top = 7;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.gap = 5;
		t.horizontalAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		t.textAlign = "center";
		t.verticalAlign = "middle";
		return t;
	};
	return ButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/CheckBoxSkin.exml'] = window.skins.CheckBoxSkin = (function (_super) {
	__extends(CheckBoxSkin, _super);
	function CheckBoxSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","alpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_up_png")
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_down_png")
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image1","source","checkbox_select_disabled_png")
				])
		];
	}
	var _proto = CheckBoxSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.alpha = 1;
		t.fillMode = "scale";
		t.source = "checkbox_unselect_png";
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.style = "cd_label";
		t.size = 14;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		return t;
	};
	return CheckBoxSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/DropDownListSkin.exml'] = window.skins.DropDownListSkin = (function (_super) {
	__extends(DropDownListSkin, _super);
	var DropDownListSkin$Skin42 = 	(function (_super) {
		__extends(DropDownListSkin$Skin42, _super);
		function DropDownListSkin$Skin42() {
			_super.call(this);
			this.skinParts = [];
			
			this.elementsContent = [this._Rect1_i(),this._Label1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
						new eui.SetProperty("_Rect1","fillAlpha",1)
					])
				,
				new eui.State ("selected",
					[
					])
				,
				new eui.State ("upAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
				,
				new eui.State ("downAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
				,
				new eui.State ("disabledAndSelected",
					[
						new eui.SetProperty("_Rect1","fillColor",0xdddddd)
					])
			];
			
			eui.Binding.$bindProperties(this, ["hostComponent.data"],[0],this._Label1,"text");
		}
		var _proto = DropDownListSkin$Skin42.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			this._Rect1 = t;
			t.fillAlpha = 1;
			t.fillColor = 0xFFFFFF;
			t.height = 30;
			t.left = 0;
			t.right = 0;
			return t;
		};
		_proto._Label1_i = function () {
			var t = new eui.Label();
			this._Label1 = t;
			t.style = "cd_label";
			t.left = 5;
			t.size = 16;
			t.textColor = 0x000000;
			t.verticalCenter = 0;
			return t;
		};
		return DropDownListSkin$Skin42;
	})(eui.Skin);

	var DropDownListSkin$Skin43 = 	(function (_super) {
		__extends(DropDownListSkin$Skin43, _super);
		function DropDownListSkin$Skin43() {
			_super.call(this);
			this.skinParts = ["labelDisplay"];
			
			this.elementsContent = [this._Rect1_i(),this.labelDisplay_i(),this._Rect2_i(),this._Image1_i()];
			this.states = [
				new eui.State ("up",
					[
					])
				,
				new eui.State ("down",
					[
					])
				,
				new eui.State ("disabled",
					[
					])
			];
		}
		var _proto = DropDownListSkin$Skin43.prototype;

		_proto._Rect1_i = function () {
			var t = new eui.Rect();
			t.bottom = 0;
			t.ellipseHeight = 7;
			t.ellipseWidth = 7;
			t.fillAlpha = 1;
			t.fillColor = 0xFFFFFF;
			t.left = 0;
			t.right = 0;
			t.strokeColor = 0x454545;
			t.strokeWeight = 1;
			t.top = 0;
			return t;
		};
		_proto.labelDisplay_i = function () {
			var t = new eui.Label();
			this.labelDisplay = t;
			t.style = "cd_label";
			t.left = 5;
			t.right = 32;
			t.size = 16;
			t.textAlign = "center";
			t.verticalCenter = 0;
			return t;
		};
		_proto._Rect2_i = function () {
			var t = new eui.Rect();
			t.fillColor = 0x454545;
			t.percentHeight = 100;
			t.right = 28;
			t.verticalCenter = 0;
			t.width = 1;
			return t;
		};
		_proto._Image1_i = function () {
			var t = new eui.Image();
			t.right = 5;
			t.source = "icon_dropdown_down_png";
			t.touchEnabled = false;
			t.verticalCenter = 0;
			return t;
		};
		return DropDownListSkin$Skin43;
	})(eui.Skin);

	function DropDownListSkin() {
		_super.call(this);
		this.skinParts = ["list","scroller","mainButton"];
		
		this.elementsContent = [this.scroller_i(),this.mainButton_i()];
	}
	var _proto = DropDownListSkin.prototype;

	_proto.scroller_i = function () {
		var t = new eui.Scroller();
		this.scroller = t;
		t.bounces = false;
		t.scrollPolicyV = "auto";
		t.percentWidth = 100;
		t.viewport = this.list_i();
		return t;
	};
	_proto.list_i = function () {
		var t = new eui.List();
		this.list = t;
		t.requireSelection = true;
		t.selectedIndex = 0;
		t.percentWidth = 100;
		t.itemRendererSkinName = DropDownListSkin$Skin42;
		return t;
	};
	_proto.mainButton_i = function () {
		var t = new eui.Button();
		this.mainButton = t;
		t.height = 30;
		t.minWidth = 110;
		t.percentWidth = 100;
		t.skinName = DropDownListSkin$Skin43;
		return t;
	};
	return DropDownListSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/HScrollBarSkin.exml'] = window.skins.HScrollBarSkin = (function (_super) {
	__extends(HScrollBarSkin, _super);
	function HScrollBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb"];
		
		this.minHeight = 8;
		this.minWidth = 20;
		this.elementsContent = [this.thumb_i()];
	}
	var _proto = HScrollBarSkin.prototype;

	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.height = 5;
		t.scale9Grid = new egret.Rectangle(3,3,2,2);
		t.source = "roundthumb_png";
		t.verticalCenter = 0;
		t.width = 50;
		return t;
	};
	return HScrollBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/HSliderSkin.exml'] = window.skins.HSliderSkin = (function (_super) {
	__extends(HSliderSkin, _super);
	function HSliderSkin() {
		_super.call(this);
		this.skinParts = ["track","thumb"];
		
		this.minHeight = 8;
		this.minWidth = 20;
		this.elementsContent = [this.track_i(),this.thumb_i()];
	}
	var _proto = HSliderSkin.prototype;

	_proto.track_i = function () {
		var t = new eui.Image();
		this.track = t;
		t.height = 6;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_sb_png";
		t.verticalCenter = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.source = "thumb_png";
		t.verticalCenter = 0;
		return t;
	};
	return HSliderSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ItemRendererSkin.exml'] = window.skins.ItemRendererSkin = (function (_super) {
	__extends(ItemRendererSkin, _super);
	function ItemRendererSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.minHeight = 50;
		this.minWidth = 100;
		this.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","source","button_down_png")
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
		];
		
		eui.Binding.$bindProperties(this, ["hostComponent.data"],[0],this.labelDisplay,"text");
	}
	var _proto = ItemRendererSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,3,8,8);
		t.source = "button_up_png";
		t.percentWidth = 100;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.bottom = 8;
		t.fontFamily = "Tahoma";
		t.left = 8;
		t.right = 8;
		t.size = 20;
		t.textAlign = "center";
		t.textColor = 0xFFFFFF;
		t.top = 8;
		t.verticalAlign = "middle";
		return t;
	};
	return ItemRendererSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/PanelSkin.exml'] = window.skins.PanelSkin = (function (_super) {
	__extends(PanelSkin, _super);
	function PanelSkin() {
		_super.call(this);
		this.skinParts = ["titleDisplay","moveArea","closeButton"];
		
		this.minHeight = 230;
		this.minWidth = 450;
		this.elementsContent = [this._Image1_i(),this.moveArea_i(),this.closeButton_i()];
	}
	var _proto = PanelSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.scale9Grid = new egret.Rectangle(2,2,12,12);
		t.source = "border_png";
		t.top = 0;
		return t;
	};
	_proto.moveArea_i = function () {
		var t = new eui.Group();
		this.moveArea = t;
		t.height = 45;
		t.left = 0;
		t.right = 0;
		t.top = 0;
		t.elementsContent = [this._Image2_i(),this.titleDisplay_i()];
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		t.bottom = 0;
		t.left = 0;
		t.right = 0;
		t.source = "header_png";
		t.top = 0;
		return t;
	};
	_proto.titleDisplay_i = function () {
		var t = new eui.Label();
		this.titleDisplay = t;
		t.fontFamily = "Tahoma";
		t.left = 15;
		t.right = 5;
		t.size = 20;
		t.textColor = 0xFFFFFF;
		t.verticalCenter = 0;
		t.wordWrap = false;
		return t;
	};
	_proto.closeButton_i = function () {
		var t = new eui.Button();
		this.closeButton = t;
		t.bottom = 5;
		t.horizontalCenter = 0;
		t.label = "close";
		return t;
	};
	return PanelSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ProgressBarSkin.exml'] = window.skins.ProgressBarSkin = (function (_super) {
	__extends(ProgressBarSkin, _super);
	function ProgressBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb","labelDisplay"];
		
		this.minHeight = 18;
		this.minWidth = 30;
		this.elementsContent = [this._Image1_i(),this.thumb_i(),this.labelDisplay_i()];
	}
	var _proto = ProgressBarSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		t.percentHeight = 100;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_pb_png";
		t.verticalCenter = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.percentHeight = 100;
		t.source = "thumb_pb_png";
		t.percentWidth = 100;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.fontFamily = "Tahoma";
		t.horizontalCenter = 0;
		t.size = 15;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		t.verticalCenter = 0;
		return t;
	};
	return ProgressBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/RadioButtonSkin.exml'] = window.skins.RadioButtonSkin = (function (_super) {
	__extends(RadioButtonSkin, _super);
	function RadioButtonSkin() {
		_super.call(this);
		this.skinParts = ["labelDisplay"];
		
		this.elementsContent = [this._Group1_i()];
		this.states = [
			new eui.State ("up",
				[
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","alpha",0.7)
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","alpha",0.5)
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_up_png")
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_down_png")
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image1","source","radiobutton_select_disabled_png")
				])
		];
	}
	var _proto = RadioButtonSkin.prototype;

	_proto._Group1_i = function () {
		var t = new eui.Group();
		t.percentHeight = 100;
		t.percentWidth = 100;
		t.layout = this._HorizontalLayout1_i();
		t.elementsContent = [this._Image1_i(),this.labelDisplay_i()];
		return t;
	};
	_proto._HorizontalLayout1_i = function () {
		var t = new eui.HorizontalLayout();
		t.verticalAlign = "middle";
		return t;
	};
	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.alpha = 1;
		t.fillMode = "scale";
		t.height = 20;
		t.smoothing = true;
		t.source = "radiobutton_unselect_png";
		t.width = 20;
		return t;
	};
	_proto.labelDisplay_i = function () {
		var t = new eui.Label();
		this.labelDisplay = t;
		t.size = 12;
		t.textAlign = "center";
		t.textColor = 0x707070;
		t.verticalAlign = "middle";
		return t;
	};
	return RadioButtonSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ScrollerSkin.exml'] = window.skins.ScrollerSkin = (function (_super) {
	__extends(ScrollerSkin, _super);
	function ScrollerSkin() {
		_super.call(this);
		this.skinParts = ["horizontalScrollBar","verticalScrollBar"];
		
		this.minHeight = 20;
		this.minWidth = 20;
		this.elementsContent = [this.horizontalScrollBar_i(),this.verticalScrollBar_i()];
	}
	var _proto = ScrollerSkin.prototype;

	_proto.horizontalScrollBar_i = function () {
		var t = new eui.HScrollBar();
		this.horizontalScrollBar = t;
		t.bottom = 0;
		t.percentWidth = 100;
		return t;
	};
	_proto.verticalScrollBar_i = function () {
		var t = new eui.VScrollBar();
		this.verticalScrollBar = t;
		t.percentHeight = 100;
		t.right = 0;
		return t;
	};
	return ScrollerSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/TextInputSkin.exml'] = window.skins.TextInputSkin = (function (_super) {
	__extends(TextInputSkin, _super);
	function TextInputSkin() {
		_super.call(this);
		this.skinParts = ["textDisplay","promptDisplay"];
		
		this.minHeight = 30;
		this.minWidth = 200;
		this.elementsContent = [this._Rect1_i(),this.textDisplay_i()];
		this.promptDisplay_i();
		
		this.states = [
			new eui.State ("normal",
				[
				])
			,
			new eui.State ("disabled",
				[
				])
			,
			new eui.State ("normalWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
			,
			new eui.State ("disabledWithPrompt",
				[
					new eui.AddItems("promptDisplay","",1,"")
				])
		];
	}
	var _proto = TextInputSkin.prototype;

	_proto._Rect1_i = function () {
		var t = new eui.Rect();
		t.ellipseHeight = 7;
		t.ellipseWidth = 7;
		t.fillColor = 0xffffff;
		t.percentHeight = 100;
		t.strokeColor = 0xBCBCBC;
		t.strokeWeight = 1;
		t.touchChildren = false;
		t.touchEnabled = false;
		t.percentWidth = 100;
		return t;
	};
	_proto.textDisplay_i = function () {
		var t = new eui.EditableText();
		this.textDisplay = t;
		t.bottom = "0";
		t.left = "10";
		t.right = "10";
		t.size = 12;
		t.textColor = 0x000000;
		t.top = "0";
		t.verticalAlign = "middle";
		t.verticalCenter = "0";
		return t;
	};
	_proto.promptDisplay_i = function () {
		var t = new eui.Label();
		this.promptDisplay = t;
		t.style = "cd_label";
		t.left = 10;
		t.right = 10;
		t.size = 12;
		t.textColor = 0xA2A2A2;
		t.touchEnabled = false;
		t.verticalCenter = 0;
		return t;
	};
	return TextInputSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/ToggleSwitchSkin.exml'] = window.skins.ToggleSwitchSkin = (function (_super) {
	__extends(ToggleSwitchSkin, _super);
	function ToggleSwitchSkin() {
		_super.call(this);
		this.skinParts = [];
		
		this.elementsContent = [this._Image1_i(),this._Image2_i()];
		this.states = [
			new eui.State ("up",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("down",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("disabled",
				[
					new eui.SetProperty("_Image1","source","off_png")
				])
			,
			new eui.State ("upAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",12)
				])
			,
			new eui.State ("downAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",12)
				])
			,
			new eui.State ("disabledAndSelected",
				[
					new eui.SetProperty("_Image2","horizontalCenter",12)
				])
		];
	}
	var _proto = ToggleSwitchSkin.prototype;

	_proto._Image1_i = function () {
		var t = new eui.Image();
		this._Image1 = t;
		t.source = "on_png";
		return t;
	};
	_proto._Image2_i = function () {
		var t = new eui.Image();
		this._Image2 = t;
		t.horizontalCenter = -11;
		t.source = "handle_png";
		t.verticalCenter = 0;
		return t;
	};
	return ToggleSwitchSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/VScrollBarSkin.exml'] = window.skins.VScrollBarSkin = (function (_super) {
	__extends(VScrollBarSkin, _super);
	function VScrollBarSkin() {
		_super.call(this);
		this.skinParts = ["thumb"];
		
		this.minHeight = 20;
		this.minWidth = 8;
		this.elementsContent = [this.thumb_i()];
	}
	var _proto = VScrollBarSkin.prototype;

	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.height = 100;
		t.horizontalCenter = 0;
		t.scale9Grid = new egret.Rectangle(3,3,2,2);
		t.source = "roundthumb_png";
		t.width = 15;
		return t;
	};
	return VScrollBarSkin;
})(eui.Skin);generateEUI.paths['resource/eui_skins/VSliderSkin.exml'] = window.skins.VSliderSkin = (function (_super) {
	__extends(VSliderSkin, _super);
	function VSliderSkin() {
		_super.call(this);
		this.skinParts = ["track","thumb"];
		
		this.minHeight = 30;
		this.minWidth = 25;
		this.elementsContent = [this.track_i(),this.thumb_i()];
	}
	var _proto = VSliderSkin.prototype;

	_proto.track_i = function () {
		var t = new eui.Image();
		this.track = t;
		t.percentHeight = 100;
		t.horizontalCenter = 0;
		t.scale9Grid = new egret.Rectangle(1,1,4,4);
		t.source = "track_png";
		t.width = 7;
		return t;
	};
	_proto.thumb_i = function () {
		var t = new eui.Image();
		this.thumb = t;
		t.horizontalCenter = 0;
		t.source = "thumb_png";
		return t;
	};
	return VSliderSkin;
})(eui.Skin);