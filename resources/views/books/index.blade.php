<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .loader {border-top-color: #3b82f6; -webkit-animation: spinner 1.5s linear infinite; animation: spinner 1.5s linear infinite;}
        @keyframes spinner {0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }}
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    <div class="container mx-auto px-4 py-10 max-w-5xl">
        <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Book Management</h1>
                <p class="text-sm text-gray-500 mt-1">Technical Test Project</p>
            </div>
            <button onclick="openModal('create')" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 px-5 rounded-lg transition duration-200 shadow-sm flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Book
            </button>
        </div>

        <div class="mb-6 flex flex-col md:flex-row gap-4 justify-between items-center">
            <div class="relative w-full md:w-1/2">
                <input type="text" id="searchInput" placeholder="Search by title or description..." 
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                    onkeyup="debounceSearch()">
                <div class="absolute top-0 left-0 mt-3.5 ml-3.5 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <div id="alertBox" class="hidden w-full md:w-1/2 px-4 py-3 rounded-lg text-sm font-medium" role="alert">
                <span id="alertMessage"></span>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs tracking-wider">
                        <tr>
                            <th class="p-4 font-semibold border-b">Book Name</th>
                            <th class="p-4 font-semibold border-b">Author</th>
                            <th class="p-4 font-semibold border-b w-1/3">Description</th>
                            <th class="p-4 font-semibold border-b">Published</th>
                            <th class="p-4 font-semibold border-b text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookTableBody" class="text-sm divide-y divide-gray-100"></tbody>
                </table>
            </div>
            
            <div id="loadingIndicator" class="hidden p-10 text-center">
                <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-10 w-10 mb-3 mx-auto"></div>
                <p class="text-gray-500 text-sm">Loading data...</p>
            </div>

            <div id="emptyState" class="hidden p-10 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                    <i class="fas fa-book text-gray-400 text-2xl"></i>
                </div>
                <p class="text-gray-500">No books found.</p>
            </div>

            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex-1 flex justify-between items-center">
                    <button onclick="changePage('prev')" id="btnPrev" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </button>
                    <span class="text-sm text-gray-600">
                        Page <span id="currentPage" class="font-semibold text-gray-900">1</span> of <span id="lastPage" class="font-semibold text-gray-900">1</span>
                    </span>
                    <button onclick="changePage('next')" id="btnNext" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="bookModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-lg shadow-xl rounded-xl bg-white overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h3 id="modalTitle" class="text-lg font-bold text-gray-800">Add New Book</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form id="bookForm" class="p-6">
                <input type="hidden" id="bookId">
                
                <div id="createFields" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5" for="book_name">Book Name</label>
                        <input type="text" id="book_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err_book_name"></p>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5" for="author">Author</label>
                        <input type="text" id="author" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err_author"></p>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-1.5" for="published_date">Published Date</label>
                        <input type="date" id="published_date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                        <p class="text-red-500 text-xs mt-1 hidden" id="err_published_date"></p>
                    </div>
                </div>

                <div class="mt-4 mb-6">
                    <label class="block text-gray-700 text-sm font-medium mb-1.5" for="description">Description</label>
                    <textarea id="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all resize-none"></textarea>
                    <p class="text-red-500 text-xs mt-1 hidden" id="err_description"></p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:outline-none shadow-sm transition-colors">
                        Save Book
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let lastPage = 1;
        let searchTimer;
        let currentMode = 'create';
        const API_URL = '/api/books';

        document.addEventListener('DOMContentLoaded', () => {
            fetchBooks();
        });

        async function fetchBooks(page = 1, search = '') {
            showLoading(true);
            try {
                const params = new URLSearchParams({ page, search });
                const response = await fetch(`${API_URL}?${params}`);
                const result = await response.json();

                if (result.status === 'success') {
                    renderTable(result.data.data);
                    updatePagination(result.data);
                }
            } catch (error) {
                showAlert('Failed to fetch data.', 'error');
            } finally {
                showLoading(false);
            }
        }

        function renderTable(books) {
            const tbody = document.getElementById('bookTableBody');
            const emptyState = document.getElementById('emptyState');
            tbody.innerHTML = '';

            if (books.length === 0) {
                emptyState.classList.remove('hidden');
                return;
            }
            
            emptyState.classList.add('hidden');

            books.forEach(book => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50 transition-colors duration-150 group';
                
                row.innerHTML = `
                    <td class="p-4 font-medium text-gray-900">${book.book_name}</td>
                    <td class="p-4 text-gray-600">${book.author}</td>
                    <td class="p-4 text-gray-500">
                        <div class="truncate max-w-xs" title="${book.description}">
                            ${book.description}
                        </div>
                    </td>
                    <td class="p-4 text-gray-600">${book.published_date}</td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <button onclick="openModal('edit', ${book.id})" class="text-blue-600 hover:text-blue-800 p-1 rounded hover:bg-blue-50 transition-colors" title="Edit Description">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteBook(${book.id})" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 transition-colors" title="Delete">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function debounceSearch() {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                const keyword = document.getElementById('searchInput').value;
                currentPage = 1;
                fetchBooks(currentPage, keyword);
            }, 500);
        }

        function updatePagination(meta) {
            currentPage = meta.current_page;
            lastPage = meta.last_page;
            
            document.getElementById('currentPage').innerText = currentPage;
            document.getElementById('lastPage').innerText = lastPage;
            
            document.getElementById('btnPrev').disabled = (currentPage === 1);
            document.getElementById('btnNext').disabled = (currentPage === lastPage);
        }

        function changePage(direction) {
            const keyword = document.getElementById('searchInput').value;
            if (direction === 'prev' && currentPage > 1) {
                fetchBooks(currentPage - 1, keyword);
            } else if (direction === 'next' && currentPage < lastPage) {
                fetchBooks(currentPage + 1, keyword);
            }
        }

        async function openModal(mode, id = null) {
            const modal = document.getElementById('bookModal');
            const form = document.getElementById('bookForm');
            const title = document.getElementById('modalTitle');
            const createFields = document.getElementById('createFields');
            const idInput = document.getElementById('bookId');
            
            document.querySelectorAll('p.text-red-500').forEach(el => el.classList.add('hidden'));
            form.reset();
            currentMode = mode;

            if (mode === 'create') {
                title.innerText = 'Add New Book';
                createFields.classList.remove('hidden');
                idInput.value = '';
            } else {
                title.innerText = 'Edit Description';
                createFields.classList.add('hidden');
                idInput.value = id;
                
                try {
                    const res = await fetch(`${API_URL}/${id}`);
                    const json = await res.json();
                    if(json.status === 'success') {
                        document.getElementById('description').value = json.data.description;
                    }
                } catch (e) {
                    console.error("Error loading detail");
                }
            }

            modal.classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookModal').classList.add('hidden');
        }

        document.getElementById('bookForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const id = document.getElementById('bookId').value;
            const description = document.getElementById('description').value;
            
            let url = API_URL;
            let method = 'POST';
            let body = {};

            if (currentMode === 'create') {
                body = {
                    book_name: document.getElementById('book_name').value,
                    author: document.getElementById('author').value,
                    published_date: document.getElementById('published_date').value,
                    description: description
                };
            } else {
                url = `${API_URL}/${id}`;
                method = 'PUT';
                body = { description: description };
            }

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                const result = await response.json();

                if (response.ok && result.status === 'success') {
                    closeModal();
                    showAlert(result.message || 'Saved successfully', 'success');
                    fetchBooks(currentPage);
                } else if (result.errors) {
                    for (const [key, messages] of Object.entries(result.errors)) {
                        const errorEl = document.getElementById(`err_${key}`);
                        if (errorEl) {
                            errorEl.innerText = messages[0];
                            errorEl.classList.remove('hidden');
                        }
                    }
                }
            } catch (error) {
                showAlert('An error occurred.', 'error');
            }
        });

        async function deleteBook(id) {
            if (!confirm('Are you sure you want to delete this book?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: {'Accept': 'application/json'}
                });
                
                if (response.ok) {
                    showAlert('Book deleted.', 'success');
                    fetchBooks(currentPage);
                } else {
                    showAlert('Failed to delete.', 'error');
                }
            } catch (error) {
                showAlert('Network error.', 'error');
            }
        }

        function showAlert(message, type) {
            const box = document.getElementById('alertBox');
            const msgEl = document.getElementById('alertMessage');
            
            box.classList.remove('hidden', 'bg-green-100', 'text-green-700', 'bg-red-100', 'text-red-700');
            
            if (type === 'success') {
                box.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
                msgEl.innerHTML = `<i class="fas fa-check-circle mr-2"></i> ${message}`;
            } else {
                box.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
                msgEl.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i> ${message}`;
            }

            box.classList.remove('hidden');
            setTimeout(() => box.classList.add('hidden'), 3000);
        }

        function showLoading(show) {
            document.getElementById('loadingIndicator').classList.toggle('hidden', !show);
            document.getElementById('bookTableBody').classList.toggle('opacity-50', show);
        }
    </script>
</body>
</html>
