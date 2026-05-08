<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gateway Loss Records - SCRUM 46 & 47</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            padding: 25px;
        }

        h1 {
            color: #333;
            margin-bottom: 10px;
            border-left: 4px solid #3498db;
            padding-left: 15px;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
            padding-left: 19px;
            font-size: 14px;
        }

        /* ============================================ */
        /* SCRUM 46: SPINNER CSS                        */
        /* ============================================ */
        
        .spinner-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            backdrop-filter: blur(3px);
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #3498db;
            border-right: 6px solid #e74c3c;
            border-bottom: 6px solid #27ae60;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        .spinner-small {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #3498db;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .btn-loading {
            opacity: 0.7;
            cursor: not-allowed;
        }

        /* ============================================ */
        /* SCRUM 47: TOAST NOTIFICATION CSS             */
        /* ============================================ */
        
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 380px;
        }

        .toast {
            padding: 14px 18px;
            border-radius: 8px;
            color: white;
            font-size: 14px;
            font-weight: 500;
            animation: slideInRight 0.3s ease-out;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .toast.success { background: linear-gradient(135deg, #27ae60, #219a52); border-left: 4px solid #fff; }
        .toast.error { background: linear-gradient(135deg, #e74c3c, #c0392b); border-left: 4px solid #fff; }
        .toast.info { background: linear-gradient(135deg, #3498db, #2980b9); border-left: 4px solid #fff; }
        .toast.warning { background: linear-gradient(135deg, #f39c12, #e67e22); border-left: 4px solid #fff; }

        .toast-close {
            background: none;
            border: none;
            color: white;
            font-size: 20px;
            cursor: pointer;
            opacity: 0.7;
        }

        .toast-close:hover { opacity: 1; }

        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }

        /* Table Styles */
        .filter-bar {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            flex-wrap: wrap;
        }

        .filter-bar select, .filter-bar button {
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        .filter-bar select {
            border: 1px solid #ddd;
            min-width: 200px;
        }

        .filter-bar button {
            background: #3498db;
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn-add {
            background: #27ae60;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background: #3498db;
            color: white;
        }

        tr:hover { background: #f8f9fa; }

        .btn-view { background: #3498db; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin: 0 3px; }
        .btn-edit { background: #f39c12; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin: 0 3px; }
        .btn-delete { background: #e74c3c; color: white; padding: 6px 12px; border: none; border-radius: 4px; cursor: pointer; margin: 0 3px; }

        button:disabled { opacity: 0.6; cursor: not-allowed; }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1050;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 12px;
            width: 90%;
            max-width: 550px;
            max-height: 85vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        .close {
            font-size: 28px;
            cursor: pointer;
            color: #999;
        }

        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }

        button[type="submit"] {
            background: #27ae60;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
            width: 100%;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Gateway Loss Records</h1>
        <div class="subtitle">SCRUM 46: Loading Spinners | SCRUM 47: Toast Notifications</div>

        <div class="filter-bar">
            <select id="providerFilter">
                <option value="">All Providers</option>
                <option value="OpenWeather">OpenWeather API</option>
                <option value="YouTube">YouTube API</option>
                <option value="Facebook">Facebook API</option>
                <option value="GoogleMaps">Google Maps API</option>
            </select>
            <button onclick="loadRecords()" id="filterBtn">🔍 Apply Filter</button>
        </div>

        <button class="btn-add" onclick="openAddModal()">➕ Add New Record</button>

        <div id="tableContainer">
            <table id="recordsTable">
                <thead>
                    <tr><th>ID</th><th>Provider</th><th>Endpoint</th><th>Status</th><th>Created At</th><th>Actions</th></tr>
                </thead>
                <tbody id="tableBody">
                    <tr><td colspan="6" style="text-align: center;"><div class="spinner-small"></div> Loading records...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SCRUM 46: Spinner Overlay -->
    <div id="spinnerOverlay" class="spinner-overlay">
        <div class="spinner"></div>
    </div>

    <!-- SCRUM 47: Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Add/Edit Modal -->
    <div id="recordModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle">Add Record</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            <form id="recordForm">
                <input type="hidden" id="recordId">
                <label>Provider *</label>
                <select id="provider" required>
                    <option value="">Select Provider</option>
                    <option value="OpenWeather">OpenWeather API</option>
                    <option value="YouTube">YouTube API</option>
                    <option value="Facebook">Facebook API</option>
                    <option value="GoogleMaps">Google Maps API</option>
                </select>
                <label>Endpoint *</label>
                <input type="text" id="endpoint" placeholder="/api/example" required>
                <label>Response Status</label>
                <input type="number" id="responseStatus" placeholder="200">
                <label>Request Payload (JSON)</label>
                <textarea id="requestPayload" rows="3" placeholder='{"key": "value"}'></textarea>
                <label>Error Message</label>
                <textarea id="errorMessage" rows="2" placeholder="Error message if any"></textarea>
                <button type="submit" id="submitBtn">💾 Save Record</button>
            </form>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📄 Record Details</h2>
                <span class="close" onclick="closeViewModal()">&times;</span>
            </div>
            <div id="viewDetails"></div>
        </div>
    </div>

    <script>
        // ============================================
        // SCRUM 47: TOAST NOTIFICATION FUNCTION
        // ============================================
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            
            const icons = { success: '✓', error: '✗', warning: '⚠', info: 'ℹ' };
            
            toast.className = `toast ${type}`;
            toast.innerHTML = `
                <span>${icons[type] || 'ℹ'}</span>
                <span>${message}</span>
                <button class="toast-close" onclick="this.parentElement.remove()">×</button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // ============================================
        // SCRUM 46: SPINNER FUNCTIONS
        // ============================================
        function showSpinner(show) {
            document.getElementById('spinnerOverlay').style.display = show ? 'flex' : 'none';
        }

        function setButtonLoading(button, loading) {
            if (loading) {
                button.disabled = true;
                button.classList.add('btn-loading');
                const originalText = button.innerHTML;
                button.setAttribute('data-original-text', originalText);
                button.innerHTML = originalText + '<span class="spinner-small"></span>';
            } else {
                button.disabled = false;
                button.classList.remove('btn-loading');
                const originalText = button.getAttribute('data-original-text');
                if (originalText) button.innerHTML = originalText;
            }
        }

        // ============================================
        // LOAD RECORDS
        // ============================================
        async function loadRecords() {
            showSpinner(true);
            const filterBtn = document.getElementById('filterBtn');
            setButtonLoading(filterBtn, true);
            
            const filter = document.getElementById('providerFilter').value;
            let url = '/api/gateway-loss-records';
            if (filter) url += `?provider=${filter}`;
            
            try {
                const response = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('Failed to load records');
                
                const records = await response.json();
                displayRecords(records);
                showToast(`✓ Loaded ${records.length} records`, 'success');
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
                document.getElementById('tableBody').innerHTML = '<tr><td colspan="6" style="text-align:center;color:red;">Failed to load records</td></tr>';
            } finally {
                showSpinner(false);
                setButtonLoading(filterBtn, false);
            }
        }

        function displayRecords(records) {
            const tbody = document.getElementById('tableBody');
            if (!records || records.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;">No records found</td></tr>';
                return;
            }
            
            tbody.innerHTML = records.map(record => `
                <tr>
                    <td>${record.id}</td>
                    <td><strong>${escapeHtml(record.provider || '-')}</strong></td>
                    <td><code>${escapeHtml(record.endpoint || '-')}</code></td>
                    <td>${record.response_status || '-'}</td>
                    <td>${record.created_at ? new Date(record.created_at).toLocaleDateString() : '-'}</td>
                    <td>
                        <button class="btn-view" onclick="viewRecord(${record.id})">View</button>
                        <button class="btn-edit" onclick="editRecord(${record.id})">Edit</button>
                        <button class="btn-delete" onclick="deleteRecord(${record.id})">Delete</button>
                    </td>
                </tr>
            `).join('');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // ============================================
        // ADD/EDIT/DELETE RECORDS WITH SPINNERS & TOASTS
        // ============================================
        async function addRecord(recordData) {
            showSpinner(true);
            const submitBtn = document.getElementById('submitBtn');
            setButtonLoading(submitBtn, true);
            
            try {
                const response = await fetch('/api/gateway-loss-records', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(recordData)
                });
                
                if (!response.ok) throw new Error('Failed to add record');
                
                showToast('✓ Record added successfully!', 'success');
                closeModal();
                loadRecords();
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
            } finally {
                showSpinner(false);
                setButtonLoading(submitBtn, false);
            }
        }

        async function updateRecord(id, recordData) {
            showSpinner(true);
            const submitBtn = document.getElementById('submitBtn');
            setButtonLoading(submitBtn, true);
            
            try {
                const response = await fetch(`/api/gateway-loss-records/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(recordData)
                });
                
                if (!response.ok) throw new Error('Failed to update record');
                
                showToast('✓ Record updated successfully!', 'success');
                closeModal();
                loadRecords();
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
            } finally {
                showSpinner(false);
                setButtonLoading(submitBtn, false);
            }
        }

        async function deleteRecord(id) {
            if (!confirm('Are you sure you want to delete this record?')) return;
            
            showSpinner(true);
            try {
                const response = await fetch(`/api/gateway-loss-records/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                });
                
                if (!response.ok) throw new Error('Failed to delete record');
                
                showToast('✓ Record deleted successfully!', 'success');
                loadRecords();
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
            } finally {
                showSpinner(false);
            }
        }

        async function viewRecord(id) {
            showSpinner(true);
            try {
                const response = await fetch(`/api/gateway-loss-records/${id}`);
                if (!response.ok) throw new Error('Failed to load record');
                
                const record = await response.json();
                const viewDetails = document.getElementById('viewDetails');
                
                viewDetails.innerHTML = `
                    <p><strong>ID:</strong> ${record.id}</p>
                    <p><strong>Provider:</strong> ${record.provider}</p>
                    <p><strong>Endpoint:</strong> ${record.endpoint}</p>
                    <p><strong>Response Status:</strong> ${record.response_status || 'N/A'}</p>
                    <p><strong>Error Message:</strong> ${record.error_message || 'None'}</p>
                    <p><strong>Created:</strong> ${record.created_at || 'N/A'}</p>
                `;
                document.getElementById('viewModal').style.display = 'flex';
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
            } finally {
                showSpinner(false);
            }
        }

        async function editRecord(id) {
            showSpinner(true);
            try {
                const response = await fetch(`/api/gateway-loss-records/${id}`);
                if (!response.ok) throw new Error('Failed to load record');
                
                const record = await response.json();
                document.getElementById('modalTitle').textContent = 'Edit Record';
                document.getElementById('recordId').value = record.id;
                document.getElementById('provider').value = record.provider;
                document.getElementById('endpoint').value = record.endpoint;
                document.getElementById('responseStatus').value = record.response_status;
                document.getElementById('errorMessage').value = record.error_message || '';
                document.getElementById('recordModal').style.display = 'flex';
            } catch (error) {
                showToast(`✗ ${error.message}`, 'error');
            } finally {
                showSpinner(false);
            }
        }

        // Form submission
        document.getElementById('recordForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const provider = document.getElementById('provider').value;
            const endpoint = document.getElementById('endpoint').value;
            
            if (!provider) { showToast('✗ Please select a provider', 'error'); return; }
            if (!endpoint) { showToast('✗ Please enter an endpoint', 'error'); return; }
            
            const id = document.getElementById('recordId').value;
            let requestPayload = document.getElementById('requestPayload').value;
            
            if (requestPayload) {
                try { requestPayload = JSON.parse(requestPayload); }
                catch (e) { showToast('✗ Invalid JSON format', 'error'); return; }
            } else { requestPayload = null; }
            
            const recordData = {
                provider, endpoint,
                response_status: document.getElementById('responseStatus').value || null,
                request_payload: requestPayload,
                error_message: document.getElementById('errorMessage').value || null
            };
            
            if (id) await updateRecord(id, recordData);
            else await addRecord(recordData);
        });

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Record';
            document.getElementById('recordForm').reset();
            document.getElementById('recordId').value = '';
            document.getElementById('recordModal').style.display = 'flex';
        }

        function closeModal() { document.getElementById('recordModal').style.display = 'none'; }
        function closeViewModal() { document.getElementById('viewModal').style.display = 'none'; }

        window.onclick = function(event) {
            if (event.target === document.getElementById('recordModal')) closeModal();
            if (event.target === document.getElementById('viewModal')) closeViewModal();
        }

        // Load records on page load
        loadRecords();
    </script>
</body>
</html>