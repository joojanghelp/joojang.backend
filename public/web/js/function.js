function commonAjaxFunc(e) {

    console.debug(e.method);
    console.debug(e.url);
    console.debug(e.data);

    // $.ajax({
    //     type: e.method,
    //     url: e.url,
    //     data: e.data.serialize(),
    //     dataType: 'json',
    //     cache: false,
    //     beforeSend: function () {
    //         // $this.prop('disabled', true);
    //         // $this.find('.status').text('Sending...');
    //     },
    //     success: function (data) {
    //         // successful request; do something with the data
    //         // if (data.result === 'success') {
    //         //     App.notify('success', 'Successfully subscribed!');
    //         //     $this.find('.status').removeClass('alert alert-success alert-danger').addClass('alert alert-success').text('Successfully subscribed!');
    //         //     //do whatever you need to do after;
    //         // } else {
    //         //     App.notify('error', data.message);
    //         //     $this.find('.status').removeClass('alert alert-success alert-danger').addClass('alert alert-danger').text(data.message);
    //         // }
    //     },
    //     error: function (jqxhr, exception) {
    //         // var message = App.getErrorMessage(jqxhr, exception);
    //         // App.notify('error', message);
    //         // $this.find('.status').removeClass('alert alert-success alert-danger').addClass('alert alert-danger').text(message);
    //     },
    //     complete: function () {
    //         // $this.prop('disabled', false);
    //     }
    // });
}