<div class="content-wrapper">
    <section class="content">
        <div class="box box-info">
            <div class="box-header">


                <h4 style="text-align:center"><b>DATA SURAT IZIN KELUARGA</b></h4>
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
                    <a href="<?php echo base_url('surat/izin_keluarga/tambah'); ?>" class="btn btn-success">Tambah Surat
                        Izin Keluarga</a>
                </p>
                <table id="data" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr class="active">
                            <th style="text-align:center">No</th>
                            <th style="text-align:center">NIK Ayah</th>
                            <th style="text-align:center">Nama Ayah</th>
                            <th style="text-align:center">NIK Anak</th>
                            <th style="text-align:center">Nama Anak</th>
                            <th style="text-align:center">Tujuan</th>
                            <th style="text-align:center">Tanda Tangan</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($surat as $surat) {
                            $anak = $this->db->query("SELECT * FROM penduduk WHERE nik='$surat->nik_anak'")->row();
                            ?>
                        <tr>
                            <td style="text-align:center"><?php echo $no; ?></td>
                            <td><?php echo $surat->nik; ?></td>
                            <td><?php echo $surat->nama; ?></td>
                            <td><?php echo $anak->nik; ?></td>
                            <td><?php echo $anak->nama; ?></td>
                            <td><?php echo $surat->tujuan_izin_keluarga; ?></td>
                            <td><?php echo $surat->nama_pejabat; ?></td>
                            <td style="text-align:center">
                                <?php 
                                if($this->session->userdata('level') != 'user'):
                                ?>
                                <a href="<?php echo base_url('surat/izin_keluarga/edit/' . $surat->id_izin_keluarga); ?>"
                                    class="btn btn-success btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                <a href="<?php echo base_url('surat/izin_keluarga/hapus/' . $surat->id_izin_keluarga); ?>"
                                    class="btn btn-danger btn-xs"
                                    onClick="return confirm('Yakin Akan Menghapus Data?');"><i
                                        class="fa fa-trash-o"></i> Hapus</a>
                                <a target="blank"
                                    href="<?php echo base_url('surat/izin_keluarga/cetak/' . $surat->id_izin_keluarga); ?>"
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