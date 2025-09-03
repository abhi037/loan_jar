function toggleSidebar(element) {
    if ($('body').hasClass('sidebar-mini')) {
        // Remove 'sidebar-mini'
        $('body').removeClass('sidebar-mini');
        // Change icon to fa-arrow-left
        $(element).find('i').removeClass('fa-arrow-right').addClass('fa-arrow-left');
    } else {
        // Add 'sidebar-mini' and remove 'layout-fullwidth'
        $('body').addClass('sidebar-mini').removeClass('layout-fullwidth');
        // Change icon to fa-arrow-right
        $(element).find('i').removeClass('fa-arrow-left').addClass('fa-arrow-right');
    }
}
