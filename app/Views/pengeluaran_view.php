<!DOCTYPE html>
<html>
<head>
  <title>Pengeluaran Harian</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { margin-bottom: 50px; text-align: center; font-size: 36px; }
    form { margin-bottom: 30px; }
    .form-cari { margin-bottom: 20px; }
    form input, form button {
      font-size: 18px;
      padding: 10px;
      margin-right: 10px;
    }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #090909ff; padding: 8px; text-align: left; }
    th { background: #07ffe6ff; }
    button { padding: 8px 15px; font-size: 16px; border: none; cursor: pointer; }
    form button { background-color: #00ff08ff; color: white; }
    .btn-edit { background-color: #f3ff07ff; color: black; }
    .btn-hapus { background-color: #dc3545; color: black; }
  </style>
</head>
<body>
  <h1>Pengeluaran Harian</h1>

  <!-- Form -->
  <form id="form">
    <input type="date" name="tanggal" required >
    <input type="text" name="keterangan" placeholder="Keterangan" required>
    <input type="number" name="nominal" placeholder="Nominal" required>
    <button type="submit">Tambah</button>
  </form>

  <form class="form-cari">
    <input type="text" id="keyword" placeholder="Cari...">
  </form>

  <!-- Tabel -->
  <table>
    <thead>
      <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Nominal</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody id="tabel"></tbody>
  </table>

<script>
function formatRupiah(angka){     
  return 'Rp. ' + Number(angka).toLocaleString('id-ID');
}

//Load Data
async function loadData(){
  let res = await fetch('/pengeluaran/list');
  let data = await res.json();
  let tbody = document.getElementById('tabel');
  tbody.innerHTML = '';

  if (!Array.isArray(data)) {
    console.error('Data bukan array:', data);
    alert('Terjadi kesalahan saat mengambil data');
    return;
  }

  data.forEach(d=>{
    tbody.innerHTML += `
      <tr>
        <td>${d.tanggal}</td>
        <td>${d.keterangan}</td>
        <td>${formatRupiah(d.nominal)}</td>
        <td>
          <button class="btn-edit" onclick="edit(${d.id},'${d.tanggal}','${d.keterangan}',${d.nominal})">Edit</button>
          <button class="btn-hapus" onclick="hapus(${d.id})">Hapus</button>
        </td>
      </tr>`;
  });
}

// Tambah data
async function defaultSubmit(e){
  e.preventDefault();
  let fd = new FormData(e.target);
  await fetch('/pengeluaran/create',{method:'POST',body:fd});
  e.target.reset();
  loadData();
}

// Hapus data
async function hapus(id){
  let res = await fetch('/pengeluaran/delete/'+id);
  let result = await res.json();
  if(result.status === 'ok'){
    document.getElementById('form').reset();
    document.querySelector('#form button').textContent = 'Tambah';
    document.getElementById('form').onsubmit = defaultSubmit;
    loadData();
  } else {
    alert('Gagal hapus data');
  }
}

// Edit data
async function edit(id,tanggal,keterangan,nominal){
  document.querySelector('[name=tanggal]').value = tanggal;
  document.querySelector('[name=keterangan]').value = keterangan;
  document.querySelector('[name=nominal]').value = nominal;
  let btn = document.querySelector('#form button');
  btn.textContent = 'Update';
  document.getElementById('form').onsubmit = async e=>{
    e.preventDefault();
    let fd = new FormData(e.target);
    await fetch('/pengeluaran/update/'+id,{method:'POST',body:fd});
    e.target.reset();
    btn.textContent = 'Tambah';
    document.getElementById('form').onsubmit = defaultSubmit;
    loadData();
  };
}

// Cari data
async function cariData(){
  const keyword = document.getElementById('keyword').value.trim();
  const url = keyword ? '/pengeluaran/search?keyword='+encodeURIComponent(keyword)
                      : '/pengeluaran/list';
  let res = await fetch(url);
  let data = await res.json();
  let tbody = document.getElementById('tabel');
  tbody.innerHTML = '';

  if (!Array.isArray(data) || data.length === 0) {
    tbody.innerHTML = "<tr><td colspan='4' style='text-align:center'>Tidak ada data ditemukan</td></tr>";
    return;
  }

  data.forEach(d=>{
    tbody.innerHTML += `
      <tr>
        <td>${d.tanggal}</td>
        <td>${d.keterangan}</td>
        <td>${formatRupiah(d.nominal)}</td>
        <td>
          <button class="btn-edit" onclick="edit(${d.id},'${d.tanggal}','${d.keterangan}',${d.nominal})">Edit</button>
          <button class="btn-hapus" onclick="hapus(${d.id})">Hapus</button>
        </td>
      </tr>`;
  });
}
document.getElementById('keyword').onkeyup = cariData;
document.getElementById('form').onsubmit = defaultSubmit;
loadData();
</script>
</body>
</html>
