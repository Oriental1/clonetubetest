$(function () {
    'use strict';
    $('#videoFile').change(ev => {
        let videoId = $(ev.target).attr('video_id');
        $(ev.target).closest('form').trigger('submit');
    })
});