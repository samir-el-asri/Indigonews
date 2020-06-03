// Scrolls the conversation div in the (conversations.show) blade view to the bottom...
// ...showing the most recent messages first on load

$(function() {
    $('#scrollingConversation').scrollTop($('#scrollingConversation')[0].scrollHeight);
    //$('#sendMessageBtn').scrollTop($('#sendMessageBtn').offset.top);
    $('html, body').animate({
        scrollTop: $('#sendMessageBtn').offset().top
    }, 1500);
});