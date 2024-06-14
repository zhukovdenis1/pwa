(function($)
{
    function inputDropDown(element, opts) {
        this.element = element;

        var $this = this;

        var defaultOptions =
        {
            getUrl: '',
            addUrl: '',
            defaultValue: ''
        };

        $this.options = $.extend(defaultOptions, opts);

        var dd = {
            id: $(element).attr('id'),
            dropDownOptions: opts
        };

        var input = $('#' + dd.id)

        ddOptions = {
            minSymbols: 0,
            limit: 10,
            delay: 0,
            url: dd.dropDownOptions.getUrl,
            placeholder: $('#' + dd.id).attr('placeholder'),
            defaultValue: $this.options.defaultValue,
            itemClick: function (item) {
                var id = $(item).data('id');
                var name = $(item).data('name');
                //ddO = dropDownObjects[dd.id];
                var ddO = $('#' + dd.id + '').data('dropDown');
                ddO.data('dropDown').close();
                ddO.data('dropDown').setValue(id);
                ddO.data('dropDown').setName(name);
                $('#' + dd.id).val(id);
            },
            addClick: function (item) {
                //var name = $(item).data('name');
                var ddo = $('#' + dd.id + '').data('dropDown');

                $.ajax({
                    url: dd.dropDownOptions.addUrl,
                    data: {value: ddo.data('dropDown').getName()},
                    dataType: 'json',
                    success: function (result) {
                        if (result.success) {
                            var id = result.data.id;
                            var name = result.data.name;
                            ddo.data('dropDown').close();
                            ddo.data('dropDown').setName(name);
                            ddo.data('dropDown').setValue(id);
                            $('#' + dd.id).val(id);
                        } else {
                            alert('Не удалось сохранить данные');
                        }
                    },
                    error: function () {
                        alert('Данные не сохранены');
                    }
                });
            },
            clearClick: function (item) {
                //var name = $(item).data('name');
                var ddo = $('#' + dd.id + '').data('dropDown');
                var id = 0;
                var name = '';
                ddo.data('dropDown').close();
                ddo.data('dropDown').setName(name);
                ddo.data('dropDown').setValue(id);
                $('#' + dd.id).val(id);
            }
        };

        var ddBox = $('<div class="dropdown _dropDown' + dd.id + '"></div>');
        input
            .attr('type', 'hidden')
            .after(ddBox)
            .data('dropDown', ddBox.dropDown(ddOptions));
    }

        $.fn.inputDropDown = function (opt) {
            return this.each(function () {
                var item = $(this), instance = item.data('inputDropDown');

                if (!instance) {
                    item.data('inputDropDown', new inputDropDown(this, opt));
                }
            });
        }

}(jQuery));