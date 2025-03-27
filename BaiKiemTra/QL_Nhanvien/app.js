const btnLogin = document.getElementById('btnLogin');
const btnAdd = document.getElementById('btnAdd');
const btnSave = document.getElementById('btnSave');
const btnCancel = document.getElementById('btnCancel');

btnLogin.addEventListener('click', async () => {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const response = await fetch('login.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `username=${username}&password=${password}`
    });
    if (await response.text() === 'success') {
        document.getElementById('loginSection').classList.add('d-none');
        document.getElementById('employeeSection').classList.remove('d-none');
        loadEmployees();
    } else alert('Sai tên đăng nhập hoặc mật khẩu!');
});

async function loadEmployees() {
    const response = await fetch('employee.php');
    const employees = await response.json();
    const table = document.getElementById('employeeTable');
    table.innerHTML = '<tr><th>ID</th><th>Tên</th><th>Giới tính</th><th>Nơi sinh</th><th>Phòng ban</th><th>Lương</th><th>Hành động</th></tr>';
    employees.forEach(emp => {
        table.innerHTML += `<tr><td>${emp.id}</td><td>${emp.name}</td><td>${emp.gender}</td><td>${emp.place}</td><td>${emp.department}</td><td>${emp.salary}</td><td><button onclick="editEmployee(${emp.id})" class="btn btn-warning">Sửa</button><button onclick="deleteEmployee(${emp.id})" class="btn btn-danger">Xóa</button></td></tr>`;
    });
}
async function deleteEmployee(id) {
    if (confirm("Bạn có chắc chắn muốn xóa nhân viên này không?")) {
        const response = await fetch('employee.php', {
            method: 'DELETE',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
        });

        const result = await response.json();
        if (result.status === 'success') {
            alert(result.message);
            await loadEmployees();
        } else {
            alert("Lỗi khi xóa nhân viên.");
        }
    }
}
async function loadEmployees() {
    const response = await fetch('employee.php');
    const employees = await response.json();
    const table = document.getElementById('employeeTable');

    table.innerHTML = `
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Giới tính</th>
            <th>Nơi sinh</th>
            <th>Phòng ban</th>
            <th>Lương</th>
            <th>Hành động</th>
        </tr>
    `;

    employees.forEach(emp => {
        table.innerHTML += `
            <tr>
                <td>${emp.id}</td>
                <td>${emp.name}</td>
                <td>${emp.gender}</td>
                <td>${emp.place}</td>
                <td>${emp.department}</td>
                <td>${emp.salary}</td>
                <td>
                    <button onclick="editEmployee(${emp.id})" class="btn btn-warning">Sửa</button>
                    <button onclick="deleteEmployee(${emp.id})" class="btn btn-danger">Xóa</button>
                </td>
            </tr>
        `;
    });
}

btnAdd.addEventListener('click', () => {
    document.getElementById('employeeForm').classList.remove('d-none');
});

btnCancel.addEventListener('click', () => {
    document.getElementById('employeeForm').classList.add('d-none');
});

btnSave.addEventListener('click', async () => {
    const name = document.getElementById('empName').value;
    const gender = document.getElementById('empGender').value;
    const place = document.getElementById('empPlace').value;
    const department = document.getElementById('empDepartment').value;
    const salary = document.getElementById('empSalary').value;

    const response = await fetch('employee.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${name}&gender=${gender}&place=${place}&department=${department}&salary=${salary}`
    });

    const result = await response.json();
    if (result.status === 'success') {
        alert(result.message);
        document.getElementById('employeeForm').classList.add('d-none');
        await loadEmployees();
    } else {
        alert('Lỗi khi thêm nhân viên');
    }
});
