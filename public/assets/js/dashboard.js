+function ($) {
    var StatusButton = function (element, options) {
        var context = this;

        $.getJSON(BASE_URL + 'admin/statuses/mget_all', function (data) {
            context.$element = $(element);
            context.options = $.extend({}, StatusButton.DEFAULTS, options);
            context.options.statuses = data;

            context.nextStatus();
            context.$element.attr('disabled', false);
            context.initPromptInterval();

            $(document).on('click', element.selector, function (event) {
                event.preventDefault();

                context.nextStatus();
                context.disable();
                context.sendData();
            });
        });
    }

    StatusButton.DEFAULTS = {
        statuses: {},
        currentStatus: -1,
        promptTextSelector: '#prompt-text'
    };

    StatusButton.prototype.sendData = function() {
        if (geoPosition.init()) {
            var context = this;

            geoPosition.getCurrentPosition(function (p) {
                $.get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + p.coords.latitude + ',' + p.coords.longitude, function (data) {
                    if (data.results) {
                        context.saveToDB(p.coords.latitude, p.coords.longitude, data.results[0].formatted_address);
                    } else {
                        context.saveToDB(null);
                    }
                });
            }, function (p) {
                context.saveToDB(null);
            });
        }
    }

    StatusButton.prototype.saveToDB = function (lat, lng, location) {
        var context = this;
        $.post(BASE_URL + 'admin/logs/madd_log', {
                user_id: USER_ID,
                status_id: this.options.currentStatus,
                lat: lat ? lat : -86,
                lng: lng ? lng : -181}, function (data) {
            context.sendToGForms(location, data);
        })
        .fail(function () {
            context.enable();
            context.previousStatus();
        });
    }

    StatusButton.prototype.sendToGForms = function(location, timeInSec) {
        var location = location ? location : 'Location not detected.';
        var time = Date(timeInSec * 1000);

        var context = this;

        $.ajax({
            type: "POST",
            url: 'https://docs.google.com/forms/d/1W0NHmWf869Ixbpa3GrfrrutNGpmKecQ15VTAv1-VVVo/formResponse',
            data: {
                "entry.1088211692": USER_NAME,
                "entry.1004697691": USERNAME,
                "entry.1452432693": USER_EMAIL,
                "entry.60465779": context.options.statuses[context.options.currentStatus].name,
                "entry.1912068217": time,
                "entry.310840202": location
            },
            type: "POST",
            dataType: "xml",
            done: function (data) {
                context.enable();
            },
            fail: function (error) {
                context.enable();
                context.previousStatus();
                console.log(error);
            }
        });
    }

    StatusButton.prototype.disable = function () {
        this.$element.attr('disabled', 'true');
        this.options.oldVal = this.$element.val();
        this.$element.val('Loading...');
    }

    StatusButton.prototype.enable = function () {
        this.$element.attr('disabled', null);
        this.$element.val(this.options.oldVal);
    }

    StatusButton.prototype.nextStatus = function () {
        var current = false;
        var context = this;

        $.each(this.options.statuses, function (key, value) {
            if (context.options.currentStatus == -1 || current) {
                context.options.currentStatus = key;

                context.$element.val(context.options.statuses[context.options.currentStatus].name);

                current = false;
                return;
            }
            if (context.options.currentStatus == key) current = true;
        });

        if (current) {
            this.options.currentStatus = -1;
            this.nextStatus();
        }
    }

    StatusButton.prototype.previousStatus = function () {
        var current = false;
        var context = this;

        var lastKey = -1;
        var statusSet = false;

        $.each(this.options.statuses, function (key, value) {
            if (key == context.options.currentStatus && lastKey != -1) {
                context.options.currentStatus = lastKey;
                context.$element.val(context.options.statuses[context.options.currentStatus].name);
                statusSet = true;
                return;
            }

            lastKey = key;
        });

        if (!statusSet) {
            context.options.currentStatus = lastKey;
            context.$element.val(context.options.statuses[context.options.currentStatus].name);
        }
    }

    StatusButton.prototype.initPromptInterval = function () {
        var context = this;

        if (this.options.promptInterval) {
            clearTimeout(this.options.promptInterval);
        } else {
            this.options.promptInterval = setTimeout(function () {
                $(context.options.promptTextSelector).html(context.getTextPrompt());
            }, 60 * 1000);

            $(context.options.promptTextSelector).html(context.getTextPrompt());
        }
    }

    StatusButton.prototype.getTextPrompt = function () {
        var prompt = '';

        $.each(this.options.statuses, function (key, value) {
            if (Math.abs((value.prompt_time % (24 * 3600)) - ((new Date().getTime() / 1000) % (24 * 3600))) <= 5 * 60) {
                prompt = 'I think it\'s time for your \'' + value.name + '\'. Click the button below.';
                return;
            }
        });

        return prompt;
    }

    $.fn.statusbutton = function () {
        this.object = new StatusButton(this);
    }

    $.fn.statusbutton.Constructor = StatusButton;

} (jQuery);

$(function () {
    $('#google-form [type="submit"]').statusbutton();
});
