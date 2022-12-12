<!-- Begin Page Content -->
<div class="container-fluid">
    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-gradient-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white text-uppercase mb-1">Jumlah Anggota
                            </div>
                            <div class="h1 mb-0 font-weight-bold text-white">
                                <?= $this->ModelUser->getUserWhere(['role_user' => 1])->num_rows(); ?></div>
                        </div>
                        <div class="col-auto"> <a href="<?= base_url('user/anggota'); ?>"><i
                                    class="fas fa-users fa-3x text-warning"></i></a> </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2 bg-gradient-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white text-uppercase mb-1">Stok Buku
                                Terdaftar
                            </div>
                            <div class="h1 mb-0 font-weight-bold text-white"> <?php $where = ['stok_buku != 0'];
                                                                                $totalstok = $this->ModelBuku->total('stok_buku', $where);
                                                                                echo $totalstok; ?> </div>
                        </div>
                        <div class="col-auto"> <a href="<?= base_url('buku'); ?>"><i
                                    class="fas fa-book fa-3x text-primary"></i></a> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2 bg-gradient-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white text-uppercase mb-1">Buku yang
                                dipinjam
                            </div>
                            <div class="h1 mb-0 font-weight-bold text-white"> <?php $where = ['jumlah_pinjam != 0'];
                                                                                $totaldipinjam = $this->ModelBuku->total('jumlah_pinjam', $where);
                                                                                echo $totaldipinjam; ?> </div>
                        </div>
                        <div class="col-auto"> <a href="<?= base_url('user'); ?>"><i
                                    class="fas fa-user-tag fa-3x text-success"></i></a> </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2 bg-gradient-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-md font-weight-bold text-white text-uppercase mb-1">Buku yang
                                dibooking
                            </div>
                            <div class="h1 mb-0 font-weight-bold text-white"> <?php $where = ['status_buku !=0'];
                                                                                $totaldibooking = $this->ModelBuku->total('status_buku', $where);
                                                                                echo $totaldibooking; ?> </div>
                        </div>
                        <div class="col-auto"> <a href="<?= base_url('user'); ?>">
                                <i class="fas fa-shopping-cart fa-3x text-danger">
                                </i>
                            </a> </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="sidebar-divider"> <!-- row table-->
    <div class="row">
        <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
            <div class="page-header"> <span class="fas fa-users text-primary mt-2 "> Data User</span> <a
                    class="text-danger" href="<?php echo base_url('user/data_user'); ?>"><i
                        class="fas fa-search mt-2 float-right"> Tampilkan</i></a> </div>
            <table class="table mt-3">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Anggota</th>
                        <th>Email</th>
                        <th>Role ID</th>
                        <th>Aktif</th>
                        <th>Member Sejak</th>
                    </tr>
                </thead>
                <tbody> <?php $i = 1;
                        foreach ($anggota as $a) { ?> <tr>
                        <td><?= $i++; ?></td>
                        <td><?= $a['nama_user']; ?></td>
                        <td><?= $a['email_user']; ?></td>
                        <td><?= $a['role_user']; ?></td>
                        <td><?= $a['status_user']; ?></td>
                        <td><?= $a['tgl_user']; ?></td>
                    </tr> <?php } ?> </tbody>
            </table>
        </div>
        <div class="table-responsive table-bordered col-sm-5 ml-auto mr-auto mt-2">
            <div class="page-header"> <span class="fas fa-book text-warning mt-2"> Data Buku</span> <a
                    href="<?= base_url('buku'); ?>"><i class="fas fa-search text-primary mt-2 float-right">
                        Tampilkan</i></a> </div>
            <div class="table-responsive">
                <table class="table mt-3" id="table-datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th>Penerbit</th>
                            <th>Tahun Terbit</th>
                            <th>ISBN</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody> <?php $i = 1;
                            foreach ($buku as $b) { ?> <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $b['judul_buku']; ?></td>
                            <td><?= $b['pengarang_buku']; ?></td>
                            <td><?= $b['penerbit_buku']; ?></td>
                            <td><?= $b['tahun_buku']; ?></td>
                            <td><?= $b['isbn_buku']; ?></td>
                            <td><?= $b['stok_buku']; ?></td>
                        </tr> <?php } ?> </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>