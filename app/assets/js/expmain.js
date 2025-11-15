if (document.querySelector('input[name="exp"]')) {
    new Cleave('input[name="exp"]', { date: true, datePattern: ['m', 'y'] });
}
if (document.querySelector('input[name="cvv"]')) {
    new Cleave('input[name="cvv"]', { numericOnly: true, blocks: [3, 4] });
}