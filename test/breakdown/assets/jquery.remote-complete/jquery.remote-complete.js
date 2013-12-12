$.fn.remoteComplete = function (options) {

    var timeOut;
    var $lastSelectedResult;

    // override options
    var opts = $.extend({}, $.fn.remoteComplete.defaults, options);

    // get copy of object
    var $self = $(this);

    // get outer values from self
    var selfOuterWidth = $self.outerWidth();
    var selfOuterHeight = $self.outerHeight();

    // create remote receiver container
    $self.remoteComplete = $('<div/>')
        .attr('id', $self.attr('id') + '-remote-complete')
        .addClass('remote-complete')
        .css('width', selfOuterWidth)
        .insertAfter($self);

    // add result container
    $self.remoteCompleteResultContainer = $('<div/>')
        .addClass('result-container')
        .appendTo($self.remoteComplete);

    // add single result container
    $self.remoteCompleteSingleSelection = $('<div/>')
        .attr('id', $self.attr('id') + '-remote-complete-single-selection')
        .addClass('remote-complete-single-selection')
        .css('margin-top', -selfOuterHeight)
        .insertAfter($self.remoteComplete);

    // resize result container with window
    $(window).resize(function (e) {
        $self.remoteComplete.css('width', $self.outerWidth());
    });

    var setResults = function (dataArray) {
        console.log(['SET RESULTS', dataArray]);
        $.each(dataArray, function () {
            var data = this;
            console.log(['DATA', data]);
            var template = opts.selectedTemplate;
            var compiledTemplate = Hogan.compile(template);
            var htmlCode = compiledTemplate.render(data);

            var $removeButton = $('<div/>').css({float: "right", margin: "0 0 0 10px"}).addClass('remove').html('<button class="btn btn-danger btn-xs">X</button>');
            var $input = $('<input/>').attr({type: 'hidden', name: $self.attr('id') + '_results[]', value: JSON.stringify(data)});

            $self.remoteCompleteSingleSelection
                .html(htmlCode)
                .prepend($removeButton)
                .append($input);

            $self.remoteCompleteSingleSelection.show();
            $self.val('');

            // show auto complete when focusing field with value
            $self.remoteCompleteSingleSelection.find('.remove').on('click', function (e) {
                e.preventDefault();
            });
        })
    };

    var priorVal = null;

    // listen to down to save current value
    $self.keydown(function (e) {
        priorVal = $self.val();
    });

    // listen to up
    $self.keyup(function (e) {
        var pressedKeyCode = e.keyCode;

        var callback = function () {
            // up/down/enter keys
            if ([38, 40, 13].indexOf(pressedKeyCode) != -1) {
                console.log([pressedKeyCode]);
                return;
            }

            // dont search when value didnt change
            if (priorVal === $self.val()) {
                console.log(['VAL', priorVal, $self.val()]);
                return;
            }

            // kill prior existing timeouts
            window.clearTimeout(timeOut);

            var search = function () {
                console.log(['SEARCH...']);

                // empty result container
                $self.remoteCompleteResultContainer.empty();

                var success = function (response) {
                    console.log(['SUCCESS', response]);
                    var results = response.results;

                    var template = opts.resultTemplate;
                    var compiledTemplate = Hogan.compile(template);

                    $.each(results, function () {
                        var $elm = $('<div/>')
                            .addClass('result')
                            .data(this)
                            .html(compiledTemplate.render(this))
                            .appendTo($self.remoteCompleteResultContainer);
                    });

                    $self.remoteComplete.show();

                    // click event for result selection
                    $self.remoteComplete.find('.result').on('click', function () {
                        setResults([$(this).data()]);
                    });

                    // close results
                    $(window).click(function (e) {
                        if ($(e.target).attr('id') != $self.attr('id')) {
                            $self.remoteCompleteResultContainer.hide();
                        }
                    })
                };

                $.ajax({
                    type: "POST",
                    url: 'post-test.php',
                    data: {
                        query: $self.val()
                    },
                    success: success,
                    dataType: 'json'
                });
            };

            // run callback with timeout
            timeOut = setTimeout(search, 200);
        };

        callback();

        priorVal = $self.val();
    });

    // show auto complete when focusing field with value
    $self.on('focus', function (e) {
        if ($self.val() != '') {
            $self.remoteCompleteResultContainer.show();
            e.stopPropagation();
        }
    });

    return {
        init: setResults
    }
};

$.fn.remoteComplete.defaults = {};