(function () {
    $('.nav li:has(ul)>a, .nav li:has(ul)>span').dropdown()
        .addClass('dropdown-toggle').append('<b class="caret"></b>')
        .parent().addClass('dropdown')
        .find('ul').addClass('dropdown-menu')
    ;
    var initDefaultElements = function() {
        $('input.remote-dropdown').each(function () {
            var $element = $(this),
                keyField = $element.data('key-field') || 'id',
                valueField = $element.data('value-field') || 'name',
                key = $element.data('key') || 'result'
            ;
            $element.select2({
                placeholder: $element.data('label') || $element.val() || "Поиск" ,
                width: '100%',
                allowClear: true,
                minimumInputLength: 2,
                initSelection: function(element, callback) {
                    var id = $(element).val(), params;

                    if (parseInt(id, 10)) {
                        params = {id: id};
                    } else {
                        params = {query: id}
                    }

                    $.get($element.data('url'), params, function(data) {
                        var results = [];
                        if ( data && data[key]) {
                            for (var i = 0; i < data[key].length; i++) {
                                results.push({id: data[key][i][keyField], text: data[key][i][valueField]});
                            }
                        }
                        callback(results[0]);
                    }, 'json');
                },
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    data: function (term, page) {
                        return {
                            query: term
                        }
                    },
                    results: function (data) {
                        var results = [];

                        if ( data && data[key] ) {
                            for ( var i in data[key] ) {
                                if (data[key].hasOwnProperty(i)) {
                                    results.push({id: data[key][i][keyField], text: data[key][i][valueField]});
                                }
                            }
                        }

                        return {results: results};
                    }
                }
            });
        });

        $('.datepicker-aware').datepicker({
            format: 'dd.mm.yyyy'
        });
    };

    $('body').on('click', '.template-remove', function() {
        $(this).closest('fieldset[name="' + $(this).data('template-for') + '"]').find('.collection-container > *:last').remove();
    });

    $('body').on('click', '.template-add', function () {
        var $fieldset = $('fieldset[name="' + $(this).data('template-for') + '"]');
        var count = $fieldset.find('.collection-container > *').length;
        var regex = new RegExp($(this).data('template-key'), 'ig');
        var template = $('*[data-for="' + $(this).data('template-for') + '"]').data('template').replace(regex, count).replace(/__count_placeholder__/ig, count+1);

        $fieldset.find('.collection-container').append(template);

        initDefaultElements();
    });

    initDefaultElements();
})();