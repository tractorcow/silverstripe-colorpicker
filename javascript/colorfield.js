(function($) {
	$.entwine('ss', function($) {
		$('input.colorfield').entwine({
			onmatch: function(e) {
				var self=$(this);
				//Calculate luminance (Photometric/digital ITU-R)
				var calcLuminance = function(rgb){
					return Math.min(1, Math.max(0, 0.2126 * (rgb.r / 255) + 0.7152 * (rgb.g / 255) + 0.0722 * (rgb.b / 255)));
				};
				var picker=$(this).ColorPicker({
					onSubmit: function(hsb, hex, rgb) {
						var col=(calcLuminance(rgb) > 0.5 ? '#000000':'#ffffff');
						self.val(hex).css({color:col, backgroundColor:'#'+hex});
					},
					onBeforeShow: function() {
						$(this).ColorPickerSetColor(this.value);
					},
					onChange: function (hsb, hex, rgb) {
						var col=(calcLuminance(rgb)  > 0.5 ? '#000000':'#ffffff');
						self.val(hex).css({color:col, backgroundColor:'#'+hex});
					}
				});
			},
			onkeyup: function() {
				$(this).ColorPickerSetColor($(this).val());
			}
		});
	});
})(jQuery);
