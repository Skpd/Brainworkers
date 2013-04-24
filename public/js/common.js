(function () {
    $('.nav li:has(ul)>a, .nav li:has(ul)>span').dropdown()
        .addClass('dropdown-toggle').append('<b class="caret"></b>')
        .parent().addClass('dropdown')
        .find('ul').addClass('dropdown-menu')
    ;
    var initDefaultElements = function() {
        $('input.remote-dropdown').each(function () {
            var $element = $(this);
            $element.select2({
                placeholder: $element.data('label') || $element.val() || "Поиск" ,
                width: 220,
                allowClear: true,
                minimumInputLength: 2,
                initSelection: function(element, callback) {
                    var id = $(element).val(), params;

                    if (parseInt(id, 10)) {
                        params = {id: id};
                    } else {
                        params = {query: id}
                    }

                    $.get($element.data('url'), params, function(r) {
                        callback(r);
                    }, 'json');
                },
//                formatSelection: function(data) {
//                    console.log(data);
//                    if (data.result) {
//                        return data.result[0].name;
//                    }
//                },
                ajax: {
                    url: $(this).data('url'),
                    dataType: 'json',
                    data: function (term, page) {
                        return {
                            query: term
                        }
                    },
                    results: function (data) {
                        var
                            results = [],
                            keyField = $element.data('key-field') || 'id',
                            valueField = $element.data('value-field') || 'name',
                            key = $element.data('key') || 'result'
                            ;

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

        $('input[type="datetime"]').datepicker();
    };

    $('body').on('click', '.template-remove', function() {
        $(this).closest('fieldset[name="' + $(this).data('template-for') + '"]').find('.collection-container > *:last').remove();
    });

    $('body').on('click', '.template-add', function () {
        var $fieldset = $('fieldset[name="' + $(this).data('template-for') + '"]');
        var count = $fieldset.find('>*').length - 1;
        var regex = new RegExp($(this).data('template-key'), 'ig');
        var template = $('*[data-for="' + $(this).data('template-for') + '"]').data('template').replace(regex, count).replace(/__count_placeholder__/ig, count+1);

        $fieldset.find('.collection-container').append(template);

        initDefaultElements();
    });

    initDefaultElements();
})();