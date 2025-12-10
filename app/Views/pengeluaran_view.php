<!DOCTYPE html>
<html>
<head>
  <title>Pengeluaran Harian</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    h1 { margin-bottom: 30px; text-align: center; font-size: 32px; }
    form { margin-bottom: 20px; }
    .form-cari { margin-bottom: 20px; }
    form input, form button { font-size: 16px; padding: 8px; margin-right: 8px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { border: 1px solid #333; padding: 8px; text-align: left; }
    th { background: #07ffe6ff; }
    button { padding: 6px 12px; font-size: 14px; border: none; cursor: pointer; }
    form button { background-color: #00ff08ff; color: white; }
    .btn-edit { background-color: #f3ff07ff; color: black; }
    .btn-hapus { background-color: #dc3545; color: white; }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
  <h1>Pengeluaran Harian</h1>

  <!-- Form -->
  <form id="form">
    <input type="date" name="tanggal" required max="<?= date('Y-m-d'); ?>">
    <input type="text" name="keterangan" placeholder="Keterangan" required>
    <input type="number" name="nominal" placeholder="Nominal" required>
    <button type="submit">Tambah</button>
  </form>

  <!-- Form Cari -->
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

// Render tabel
function renderTable(data){
  const tbody = $("#tabel");
  tbody.html("");
  if (!Array.isArray(data) || data.length === 0) {
    tbody.html("<tr><td colspan='4' style='text-align:center'>Tidak ada data ditemukan</td></tr>");
    return;
  }
  $.each(data, function(i, d){
    tbody.append(`
      <tr>
        <td>${d.tanggal}</td>
        <td>${d.keterangan}</td>
        <td>${formatRupiah(d.nominal)}</td>
        <td>
          <button class="btn-edit" onclick="edit(${d.id},'${d.tanggal}','${d.keterangan}',${d.nominal})">Edit</button>
          <button class="btn-hapus" onclick="hapus(${d.id})">Hapus</button>
        </td>
      </tr>`);
  });
}

// Load Data
function loadData(){
  $.ajax({
    url: "/pengeluaran/list",
    method: "GET",
    dataType: "json",
    success: function(res){ 
      if(res.status === "ok"){ renderTable(res.data); }
      else { alert(res.message || "Gagal load data"); }
    },
    error: function(xhr){ console.error(xhr.responseText); alert("Gagal mengambil data"); }
  });
}

// Tambah data
$("#form").on("submit", function(e){
  e.preventDefault();
  $.ajax({
    url: "/pengeluaran/create",
    method: "POST",
    data: $(this).serialize(),
    dataType: "json",
    success: function(res){
      if(res.status === "ok"){ $("#form")[0].reset(); loadData(); }
      else { alert(res.message || "Gagal tambah data"); }
    },
    error: function(xhr){ console.error(xhr.responseText); alert("Error tambah data"); }
  });
});

// Hapus data
function hapus(id){
  $.ajax({
    url: "/pengeluaran/delete/"+id,
    method: "POST",
    dataType: "json",
    success: function(res){
      if(res.status === "ok"){ resetForm(); loadData(); }
      else { alert(res.message || "Gagal hapus data"); }
    },
    error: function(xhr){ console.error(xhr.responseText); alert("Error hapus data"); }
  });
}

// Edit data
function edit(id,tanggal,keterangan,nominal){
  $("[name=tanggal]").val(tanggal);
  $("[name=keterangan]").val(keterangan);
  $("[name=nominal]").val(nominal);
  let btn = $("#form button");
  btn.text("Update");

  $("#form").off("submit").on("submit", function(e){
    e.preventDefault();
    $.ajax({
      url: "/pengeluaran/update/"+id,
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function(res){
        if(res.status === "ok"){ resetForm(); loadData(); }
        else { alert(res.message || "Gagal update data"); }
      },
      error: function(xhr){ console.error(xhr.responseText); alert("Error update data"); }
    });
  });
}

// Reset form ke mode tambah
function resetForm(){
  $("#form")[0].reset();
  $("#form button").text("Tambah");
  $("#form").off("submit").on("submit", function(e){
    e.preventDefault();
    $.ajax({
      url: "/pengeluaran/create",
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      success: function(res){
        if(res.status === "ok"){ $("#form")[0].reset(); loadData(); }
        else { alert(res.message || "Gagal tambah data"); }
      },
      error: function(xhr){ console.error(xhr.responseText); alert("Error tambah data"); }
    });
  });
}

// Cari data
$("#keyword").on("keyup", function(){
  var keyword = $(this).val().trim();
  var url = keyword ? "/pengeluaran/search?keyword="+encodeURIComponent(keyword) : "/pengeluaran/list";
  $.ajax({
    url: url,
    method: "GET",
    dataType: "json",
    success: function(res){ 
      if(res.status === "ok"){ renderTable(res.data); }
      else { alert(res.message || "Gagal mencari data"); }
    },
    error: function(xhr){ console.error(xhr.responseText); alert("Error saat mencari data"); }
  });
});

// Load awal
loadData();
</script>
</body>
</html>
