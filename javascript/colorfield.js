(function($) {
    $.entwine('ss', function($) {
        $('.ColorPickerInput').entwine({
            onmatch: function(e) {
                var self=$(this);
                
                var picker=$(this).ColorPicker({
                    onSubmit: function(hsb, hex, rgb) {
                        var mid=(rgb.r+rgb.g+rgb.b)/3;
                        var col=(mid>127 ? '#000000':'#ffffff');
                        self.val(hex).css({color:col, backgroundColor:'#'+hex});
                    },
                    onBeforeShow: function() {
                        $(this).ColorPickerSetColor(this.value);
                    },
                    onChange: function (hsb, hex, rgb) {
                        var mid=(rgb.r+rgb.g+rgb.b)/3;
                        var col=(mid>127 ? '#000000':'#ffffff');
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