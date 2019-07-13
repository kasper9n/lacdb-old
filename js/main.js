
$(function() {
	$(".description-modal").fadeOut(0).removeClass("hidden");
});

function openDescription() {
    $(function() {
        $(".description-modal").fadeIn(150);
		$("textarea").autoresize().select();
    });
}

function closeDescription() {
    $(function() {
        $(".description-modal").fadeOut(150);
    })
}
