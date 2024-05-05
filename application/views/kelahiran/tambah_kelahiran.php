<div class="content-wrapper">
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h4 style="text-align:center"><b>TAMBAH DATA KELAHIRAN</b></h4>
                <hr>
            </div>

            <div class="box-body">
                <div class="card-body">

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

                    <form action="<?php echo base_url('kelahiran/proses_tambah'); ?>" method="post">
                        <!-- kolom ke-1 -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ID Monitoring</label>
                                    <input type="hidden" name="" value="">
                                    <input type="text" name="id_monitoring" class="form-control" required />
                                </div>

                                <div class="form-group">
                                    <label>Tanggal Lahir</label>
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>

                                        <input type="date" name="tanggal_lahir" class="form-control pull-right">
                                    </div>
                                </div>

                            </div>
                            <div class="col-rg-6">

                            </div>
                            <div class="bootstrap-timepicker">
                                <div class="form-group">
                                    <label>Pukul</label>
                                    <div class="input-group">
                                        <input type="time" name="pukul" id="pukul" class="form-control timepicker" required>
                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control" required>
                                    <option value="" selected disabled>- pilih -</option>
                                    <option value="Laki Laki">Laki Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">
                            <label>Nama anak</label>
                            <input type="text" name="nama" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Hari dilahirkan</label>
                            <div>
                                <select name="hari" class="form-control" required>
                                    <option value="Senin">
                                        Senin
                                    </option>
                                    <option value="Selasa">
                                        Selasa
                                    </option>
                                    <option value="Rabu">
                                        Rabu
                                    </option>
                                    <option value="Kamis">
                                        Kamis
                                    </option>
                                    <option value="Jumat">
                                        jumat
                                    </option>
                                    <option value="Sabtu">
                                        Sabtu
                                    </option>
                                    <option value="Minggu">
                                        Minggu
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Jenis kelahiran</label>
                            <select name="jenis_kelahiran" class="form-control" required>
                                <option value="normal">Normal</option>
                                <option value="caesar">Caesar</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Anak ke-</label>
                            <input type="text" name="anak_ke" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Berat bayi</label>
                            <input type="text" name="berat_bayi" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Panjang bayi</label>
                            <input type="text" name="panjang_bayi" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label>NIK Ayah</label>
                            <input type="text" name="nik_ayah" class="form-control" required />
                        </div>
                        <div class=" form-group">
                            <label>Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" required />
                        </div><br>

                        <div class="form-group">
                            <label>NIK Ibu</label>
                            <input type="text" name="nik_ibu" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" required />
                        </div>
                        <div class="form-group">
                            <label>Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" required />
                        </div>
                        <!-- kolom ke-2 -->
                        <div class="row">
                            <div class="col-lg-6">

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="3" required></textarea>
                                </div>

                                <div class="form-group">
                                    <label>RT</label>
                                    <input type="text" name="rt" class="form-control" required />
                                </div>
                                <div class="form-group">
                                    <label>RW</label>
                                    <select name="rw" class="form-control" required>
                                        <option value="" selected disabled>- pilih -</option>
                                        <option value="Kepala Dusun Krajan 1">Kepala Dusun Krajan 1</option>
                                        <option value="Kepala Dusun Krajan 2">Kepala Dusun Krajan 2</option>
                                        <option value="Kepala Dusun Sukamaju">Kepala Dusun Sukamaju</option>
                                        <option value="Kepala Dusun Sukamulya">Kepala Dusun Sukamulya
                                        </option>
                                        <option value="Kepala Dusun WarnaJaya">Kepala Dusun WarnaJaya
                                        </option>
                                        <option value="Perumahan Bumi Karawang Permai">Perumahan Bumi
                                            Karawang
                                            Permai</option>
                                        <option value=">Perumahan Gading Elok 2">Perumahan Gading Elok 2
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>KETERANGAN</label>
                                    <input type="text" name="keterangan" class="form-control" rows="2" required />
                                </div>

                            </div>
                        </div>
                        <center>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Simpan</button>
                                <a href="<?php echo base_url('kelahiran/tampil'); ?>" class="btn btn-danger">Batal</a>
                            </div>
                        </center>
                </div>
            </div>
            </form>
        </div>
</div>
</div>
</section>
</div>