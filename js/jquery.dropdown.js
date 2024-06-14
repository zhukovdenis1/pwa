(function($)
{
    function dropDown(element, opts)
    {
        this.element = element;

        var $this = this;

        var defaultOptions =
        {
            url: '',
            limit: 0,
            minSymbols: 0,
            height: 0,
            delay: 500,
            itemTextField: 'name',//key параметр json
            placeholder: '',
            defaultValue: '',
            itemClick: function(result) {},
            addClick: function(result) {},
            change: function() {}
        };

        var xhrCounter = 0;
        var xhrObject = null;

        this.options = $.extend(defaultOptions, opts);

        var listDD = '<div class="_listDD list-dd" style="display: none"></div>';

        $(element).append(
            '<div class="_inputWrap input-wrap">' +
                '<input type="text" value="' + $this.options.defaultValue + '" placeholder="' + $this.options.placeholder + '" />' +
                '<div class="_listDDClose close-button fa fa-caret-up" style="display: none;"></div>' +
                '</div>' +
                listDD
        );

        this.list = $(element).find('._listDD');
        this.input = $(element).find('input').eq(0);
        this.value = '';

        var listWidth = $(element).find('._inputWrap').width();
        $(element).find('._listDD').css('min-width', listWidth);

        $(element).find('._listDDClose').click(function()
        {
            $this.close();
        });

        this.input.keyup(function()
        {
            $this.triggerDropDown();
        });

        this.input.click(function(e)
        {
            $this.triggerDropDown();
        });

        this.triggerDropDown = function()
        {
            this.close();
            var text = $(this.input).val();
            if (text.length >= this.options.minSymbols)
            {
                this.open(text);
            }

            $this.options.change();

        };

        this.close = function()
        {
            this.list.empty().hide();
            $(this.element).find('._listDDClose').hide();
        };

        this.setData = function(data)
        {
            $(this.element).data({data: data});
        };

        this.getData = function(item)
        {
            var data = $(this.element).data('data');
            if (typeof(data) == 'undefined')
            {
                return null;
            }
            return item ? data[item] : data;
        };

        this.setValue = function(value)
        {
            this.value = value;
        };

        this.getValue = function()
        {
            return this.value;
        };

        this.setName = function(value)
        {
            this.input.val(value);
        };

        this.getName = function()
        {
            return this.input.val();
        };

        this.reset = function()
        {
            this.setData({});
            this.setValue('');
            this.setName('');
        };

        this.open = function(text)
        {
            this.list.empty().show().append('<div class="loader fa fa-spinner"></div>');

            if (xhrCounter && xhrObject) {xhrObject.abort()}

            xhrCounter++;

            setTimeout(function()
                {
                    xhrObject = $.ajax({
                        url: $this.options.url,
                        data: {text: text, limit: $this.options.limit},
                        dataType: 'json',
                        success: function(result)
                        {
                            xhrCounter--;
                            //options.success(result);
                            var ul = ($this.options.height) ? $('<ul style="max-height:' + $this.options.height + 'px;overflow-y:scroll;" ></ul>') : $('<ul></ul>');
                            var addRow = $('<span class="add-row"><span class="fa fa-plus"></span> "<span class="_val"></span>"</span>');
                            var clearRow = $('<span class="clear-row"><span class="fa fa-drop"></span>Очистить</span>');
                            $this.list.empty().append(ul).append(addRow).append(clearRow);
                            addRow.click(function()
                            {
                                $this.options.addClick(this);
                            });
                            clearRow.click(function()
                            {
                                $this.options.clearClick(this);
                            });
                            $($this.element).find('._listDDClose').show();

                            var numItems = 0;
                            $.each(result, function(key,item)
                            {
                                numItems++;
                                var li = $('<li>' + item[$this.options.itemTextField] + '</li>');
                                li.data(item);
                                li.appendTo(ul);
                                li.click(function()
                                {
                                    $this.options.itemClick(this);
                                });
                            });

                            addRow.find('._val').empty().text(text);
                            addRow.data('name', text);
                            $($this.element).find('._listDDClose').show();

                            /*if (numItems)
                             {
                             $($this.element).find('._listDDClose').show();
                             }
                             else
                             {
                             alert('hi');

                             /*var li = $('<li> + ' + text + '</li>');
                             li.data({name: text});
                             li.appendTo(ul);
                             li.click(function()
                             {
                             $this.options.addClick(this);

                             });
                             $($this.element).find('._listDDClose').show();
                             }*/

                        },
                        error: function()
                        {
                            xhrCounter--;
                        }
                    });
                },
                $this.options.delay
            );
        }
    }


    $.fn.dropDown = function(opt)
    {
        return this.each(function()
        {
            var item = $(this), instance = item.data('dropDown');

            if (!instance)
            {
                item.data('dropDown', new dropDown(this, opt));
            }
        });
    }

}(jQuery));