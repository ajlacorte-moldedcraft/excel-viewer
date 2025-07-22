function convertToPhilippineTime(isoString) {
    try {
        const date = new Date(isoString);
        const options = {
            timeZone: 'Asia/Manila',
            year: 'numeric',
            month: 'short',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: false
        };
        return new Intl.DateTimeFormat('en-PH', options).format(date);
    } catch (e) {
        console.error("Invalid ISO string:", isoString);
        return isoString;
    }
}

$(function () {
    $('.timestamp').each(function () {
        const iso = $(this).data('time');
        const converted = convertToPhilippineTime(iso);
        $(this).text(converted);
    });
});
