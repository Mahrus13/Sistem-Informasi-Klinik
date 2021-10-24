<?php
include('koneksi.php');
$tanggal_tebus = mysqli_query($connect,"SELECT TanggalTebus FROM resep GROUP BY TanggalTebus");
$total_harga = mysqli_query($connect,"SELECT SUM(TotalHarga) AS harga FROM resep GROUP BY TanggalTebus");
?>

<script src="js/Chart.js" type="text/javascript"></script>
<body>
    <div id="luar">
        <center> <br>
        <h1>Data Pemasukan</h1> <br>
            <div>
                <canvas id="harga"></canvas>
            </div>
        </center>
        <br><br>
        <center>
        <h1>Data laporan Pasien</h1> <br>
        <div>
            <canvas id="myPieChart"></canvas>
        </div>
        </center>
    </div>
</body>


<script>
    var ctx = document.getElementById("harga").getContext('2d');
    var mychart = new Chart(ctx, {
        type: 'bar', 
        data: {
            labels:[<?php while($row = mysqli_fetch_array($tanggal_tebus)) {echo '"'.$row['TanggalTebus']. '",';} ?>],
            datasets:
            [
                {
                   label: 'Data Pemasukan Keuntungan',
                   data: [<?php while($row = mysqli_fetch_array($total_harga)) {echo '"'.$row['harga'].'",';}?>],
                   backgroundColor:['#7FFFD4','#17BEBB','#FFC914','#7FFF00','#9932CC','#008000','#17BEBB'],
                   borderWidth: 1 
                }
            ]
        },
        options:{
            scales:{
                yAxes: [{
                    ticks:{
                        beginAtZero:true
                    }
                }]
            }
        }
    })
</script>

<?php
    function query($sql){
    global $connect;
    $perintah=mysqli_query($connect,$sql);
    if(!$perintah) die("Gagal melakukan koneksi".mysqli_connect_error());
    return $perintah;
    }

    function Chart_Tampil_JK(){
    $sql="SELECT SUM(IF(GenderPasien='L',1,0)) AS jumlah_pria, SUM(IF(GenderPasien='P',1,0)) AS jumlah_perempuan FROM pasien";
    $perintah=query($sql);
    return $perintah;
    }
?>
<?php
 //panggil function data mahasiswa berdasarkan jenis kelamin
 $tampil=Chart_Tampil_JK();
 $tampilkan=mysqli_fetch_array($tampil);
 
 ?>


<script type="text/javascript">

// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#292b2c';

// Pie Chart Example
var ctx = document.getElementById("myPieChart");
var myPieChart = new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ["Laki laki","Perempuan"],
    datasets: [{
    label: '',
      data: [<?php echo $tampilkan['jumlah_pria']; ?>, <?php echo $tampilkan['jumlah_perempuan']; ?>],
      backgroundColor: ['#007bff', '#dc3545'],
    }],
  },
});
</script>
