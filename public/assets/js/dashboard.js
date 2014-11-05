var CURRENT_DATE;

function get12HourTime(date)
{   
    var ampm = 'AM';
    var hr = date.getHours();
    var min = date.getMinutes();

    if (hr > 12) {
        hr = hr % 12;
        ampm = 'PM';
    }

    return hr + ':' + (min < 10 ? '0' + min : min) + ' ' + ampm;
}

+function ($) {
    var StatusButton = function (element, options) {
        var context = this;
        var startTime = new Date().getTime();

        $.getJSON(BASE_URL + 'admin/statuses/mget_all', function (data) {
            $.getJSON(BASE_URL + 'admin/mget_time', function (timeInMilis) {
                CURRENT_DATE = new Date(timeInMilis * 1000 + (new Date().getTime() - startTime));

                context.$element = $(element);
                context.options = $.extend({}, StatusButton.DEFAULTS, options);
                context.options.statuses = data;

                if (context.options.currentStatus <= 0) context.options.currentStatus = -1;
                // Should not be able to time-in if past 12:00PM condition
                // var skip = true;
                // var current = context.options.currentStatus == -1 ? true : false;
                // $.each(context.options.statuses, function (key, value) {
                //     if (!skip && current) {
                //         if ((CURRENT_DATE.getTime() / 1000) % (24 * 3600) > value.prompt_time % (24 * 3600)) {
                //             context.options.currentStatus = key;
                //         } else {
                //             return;
                //         }
                //     }

                //     if (key == context.options.currentStatus) current = true;
                //     skip = !skip;
                // });
                
                // no JOB # on time in
                var timeIn = false;
                $.each(context.options.statuses, function (key, value) {
                    if (key == context.options.currentStatus) {
                        if (timeIn) {
                            context.options.jobNo = null;
                        }
                        return;
                    }

                    timeIn = !timeIn;
                });

                if (context.options.jobNo) {
                    $(context.options.jobNoTextSelector).html('Current JOB #: ' + context.options.jobNo);
                    $(context.options.jobNoModalSelector + ' input[type="text"]').val(context.options.jobNo);
                } 

                context.nextStatus();
                context.$element.attr('disabled', false);
                context.initPromptInterval();

                $(document)
                    .on('click', element.selector, function (event) {
                        event.preventDefault();

                        if (context.options.jobNo) {
                            $(context.options.jobNoModalSelector + ' input[type="submit"]').click();
                            // $(context.options.jobNoTextSelector).html((context.options.jobNo = null));
                        } else {
                            $(context.options.jobNoModalSelector).modal('show');
                        }
                    })
                    .on('click', context.options.jobNoModalSelector + ' input[type="submit"]', function (event) {
                        var jobNo = $(context.options.jobNoModalSelector + ' input[type="text"]').val();
                        if (!jobNo) {
                            return;
                        }

                        if (!context.options.jobNo) {
                            $(context.options.jobNoModalSelector).modal('hide');
                            $(context.options.jobNoTextSelector).html('Current JOB #: ' + (context.options.jobNo = jobNo));
                        } else {
                            $(context.options.jobNoModalSelector + ' input[type="text"]').val('');
                        }

                        // context.nextStatus();
                        context.disable();
                        context.sendData();
                    });
            });
        });
    }

    StatusButton.DEFAULTS = {
        statuses: {},
        currentStatus: -1,
        promptTextSelector: '#prompt-text',
        timeTextSelector: '.time span',
        jobNoModalSelector: '#modal-jobno',
        jobNo: null,
        jobNoTextSelector: '#jobno-text'
    };

    StatusButton.prototype.setTime = function () {
        var thres = 60;
        var context = this;

        $(context.options.timeTextSelector).html(get12HourTime(CURRENT_DATE));
        setTimeout(function () {
            CURRENT_DATE.setMinutes(CURRENT_DATE.getMinutes() + 1);
            $(context.options.timeTextSelector).html(get12HourTime(CURRENT_DATE));
        }, (thres - CURRENT_DATE.getSeconds()) * 1000);
    }

    StatusButton.prototype.sendData = function () {
        if (geoPosition.init()) {
            var context = this;

            geoPosition.getCurrentPosition(function (p) {
                $.get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' + p.coords.latitude + ',' + p.coords.longitude, function (data) {
                    if (data.results && data.results.length > 0) {
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
                lng: lng ? lng : -181,
                job_no: this.options.jobNo,
                location: location ? location : 'Location not detected.'
            }, function (data) {
            context.sendToGForms(location, data);
        })
        .fail(function () {
            context.enable();
            context.previousStatus();
        });
    }

    StatusButton.prototype.sendToGForms = function(location, timeInSec) {
        // var location = location ? location : 'Location not detected.';
        // var time = Date(timeInSec * 1000);

        var context = this;

        // $.ajax({
        //     url: 'https://docs.google.com/forms/d/1W0NHmWf869Ixbpa3GrfrrutNGpmKecQ15VTAv1-VVVo/formResponse',
        //     data: {
        //         "entry.1088211692": USER_NAME,
        //         "entry.1004697691": USERNAME,
        //         "entry.1452432693": context.options.jobNo,
        //         "entry.60465779": context.options.statuses[context.options.currentStatus].name,
        //         "entry.1912068217": time,
        //         "entry.310840202": location
        //     },
        //     type: "POST",
        //     dataType: "xml",
        //     done: function (data) {
        //         console.log(data);
        //         context.enable();
        //         context.nextStatus();
        //     },
        //     error: function (error) {
        //         context.enable();
        //         context.nextStatus();
        //         // context.previousStatus();
        //         console.log(error);
        //     }
        // });
        
        context.enable();
        context.nextStatus();

        if (!$(context.options.jobNoModalSelector + ' input[type="text"]').val()) {
            $(context.options.jobNoTextSelector).html((context.options.jobNo = null));
        }
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
            if (context.options.currentStatus <= 0 || current) {
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
            clearInterval(this.options.promptInterval);
        } else {
            this.options.promptInterval = setInterval(function () {
                $(context.options.promptTextSelector).html(context.getTextPrompt());

                context.setTime();
            }, 60 * 1000);

            $(context.options.promptTextSelector).html(context.getTextPrompt());
            context.setTime();
        }
    }

    StatusButton.prototype.getTextPrompt = function () {
        var value = this.options.statuses[this.options.currentStatus];

        if (Math.abs((this.options.statuses[this.options.currentStatus].prompt_time % (24 * 3600)) - ((CURRENT_DATE.getTime() / 1000) % (24 * 3600))) <= 5 * 60) {
            return 'I think it\'s time for your \'' + value.name + '\'. Click the button below.';
        } else {
            return '';
        }
    }

    $.fn.statusbutton = function () {
        this.object = new StatusButton(this, {
            currentStatus: USER_STATUS,
            jobNo: USER_JOBNO
        });
    }

    $.fn.statusbutton.Constructor = StatusButton;

} (jQuery);

$(function () {
    $('#google-form [type="submit"]').statusbutton();
});
