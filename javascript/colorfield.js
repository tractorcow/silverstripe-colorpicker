var ColorFieldHandler = function(){
	var $ = jQuery;
	
	this.initAll = function(){
		var fnc = this.initById;
		$('.ColorPickerInput').each(function(){
			fnc('#' + $(this).attr('id'));
		});
	}
	
	this.initById = function(id){
		$(id).ColorPicker({
			onChange: function(hsb, hex, rgb) {
				var mid = (rgb.r + rgb.g + rgb.b) / 3;
				var col = mid > 127 ? '#000000' : '#ffffff';
				$(id).val(hex).css({color:col, backgroundColor:'#' + hex});
			},
			onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		}).bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
		});
	}
}

CFHandler = new ColorFieldHandler();

//check if prototype wrapper is required.
if(typeof Behaviour == 'object'){
	Behaviour.register({
		'.ColorPickerInput' : {
			initialise : function(){
				CFHandler.initById('#' + this.id);
			}
		}	
	});
}

jQuery(document).ready(function(){
	CFHandler.initAll();
});