<div class="content-wrapper">
    <section class="content">
        <div class="box box-info">
            <div class="box-header">


                <h4 style="text-align:center"><b>DATA SURAT KEMATIAN</b></h4>
                <hr>
            </div>

            <div class="box-body table-responsive">

                <?php
                if ($this->session->flashdata('sukses')) {
                    ?>
                <div class="callout callout-success">
                    <p style="font-size:14px">
                        <i class="fa fa-check"></i> <?php echo $this->session->flashdata('sukses'); ?>
                    </p>
                </div>
                <?php
                }
                ?>
                <p>
                    <a href="<?php echo base_url('surat/surat_kematian/tambah'); ?>" class="btn btn-success">Tambah
                        Surat Kematian</a>
                </p>
                <table id="data" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="active">
                            <th style="text-align:center">No</th>
                            <th style="text-align:center">NIK</th>
                            <th style="text-align:center">Nama</th>
                            <th style="text-align:center">Tempat</th>
                            <th style="text-align:center">Hari/Tanggal</th>
                            <th style="text-align:center">Jam</th>
                            <th style="text-align:center">Nama Pelapor</th>
                            <th style="text-align:center">Tanda Tangan</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($surat as $surat) {
                            $pelapor = $this->db->query("SELECT * FROM penduduk WHERE nik='$surat->nik_pelapor'")->row();
                            ?>
                        <tr>
                            <td style="text-align:center"><?php echo $no; ?></td>
                            <td><?php echo $surat->nik; ?></td>
                            <td><?php echo $surat->nama; ?></td>
                            <td><?php echo $surat->tempat_kematian; ?></td>
                            <td><?php echo $surat->hari_kematian; ?>/
                                <?= date('d F Y', strtotime($surat->tanggal_kematian)); ?></td>
                            <td><?php echo $surat->jam_kematian; ?></td>
                            <td><?php echo $pelapor->nama; ?></td>
                            <td><?php echo $surat->nama_pejabat; ?></td>
                            <td style="text-align:center">
                                <?php 
                                if($this->session->userdata('level') != 'user'):
                                ?>
                                <a href="<?php echo base_url('surat/surat_kematian/edit/' . $surat->id_surat_kematian); ?>"
                                    class="btn btn-success btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                <a href="<?php echo base_url('surat/surat_kematian/hapus/' . $surat->id_surat_kematian); ?>"
                                    class="btn btn-danger btn-xs"
                                    onClick="return confirm('Yakin Akan Menghapus Data?');"><i
                                        class="fa fa-trash-o"></i> Hapus</a>
                                <a target="blank"
                                    href="<?php echo base_url('surat/surat_kematian/cetak/' . $surat->id_surat_kematian); ?>"
                                    class="btn btn-info btn-xs"><i class="fa fa-print"></i> Cetak</a>
                                <?php endif ?>

                        </tr>
                        </td>

                        <?php
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
    </section>
</div>