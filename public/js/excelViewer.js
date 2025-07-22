document.addEventListener('DOMContentLoaded', function () {
    
	const fileUrl = document.getElementById('gridctr').dataset.file;
	const gridContainer = document.getElementById('gridctr');
	const sheetSelect = document.getElementById('sheet-select');
	let workbook = null;
	const grid = canvasDatagrid({ parentNode: gridContainer, data: [] });

	fetch(fileUrl).then(response => response.arrayBuffer()).then(arrayBuffer => {
		const data = new Uint8Array(arrayBuffer);
		workbook = XLSX.read(data, { type: 'array' });

		// Populate dropdown
		sheetSelect.innerHTML = '';
		workbook.SheetNames.forEach(sheetName => {
			const option = document.createElement('option');
			option.value = sheetName;
			option.textContent = sheetName;
			sheetSelect.appendChild(option);
		});

		loadSheet(workbook.SheetNames[0]); // Load first sheet by default
	});

	sheetSelect.addEventListener('change', function () {
		loadSheet(this.value);
	});

	function loadSheet(sheetName) {
		const worksheet = workbook.Sheets[sheetName];
		const rawData = XLSX.utils.sheet_to_json(worksheet, { header: 1, defval: '' });

		if (rawData.length === 0) {
			grid.data = [];
			return;
		}

		const columnCount = Math.max(...rawData.map(row => row.length));
		const headers = Array.from({ length: columnCount }, (_, i) => `Column ${i + 1}`);

		const jsonData = rawData.map(row => {
			const obj = {};
			headers.forEach((header, i) => {
				obj[header] = row[i] ?? '';
			});
			return obj;
		});

		grid.data = jsonData;
	}
});
