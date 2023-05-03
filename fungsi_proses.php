<?php
function dec(){
	return 4;
}

function jumlah_data_latih($db_object, $where=null){
	$sql = "SELECT COUNT(*) FROM data_latih ".$where;
	$res = $db_object->db_query($sql);
	$rows = $db_object->db_fetch_array($res);
	return $rows[0];
}

/**
 * 
 * @param type $db_object
 * @param type $id_data_uji
 * @param type $jenis_kelamin
 * @param type $usia
 * @param type $sekolah
 * @param type $jawaban_a
 * @param type $jawaban_b
 * @param type $jawaban_c
 * @param type $jawaban_d
 * @return array
 */
function ProsesNaiveBayes($db_object, $id_data_uji=0, $jenis_kelamin, $usia, $sekolah, 
        $jawaban_a, $jawaban_b, $jawaban_c, $jawaban_d, $show_perhitungan=true){
	
	$jumlah_data = jumlah_data_latih($db_object);//jumlah data latih
	$jumlah_sanguin = jumlah_data_latih($db_object, " WHERE kelas_asli='Sanguin'");//jumlah sanguin
	$jumlah_koleris = jumlah_data_latih($db_object, " WHERE kelas_asli='Koleris'");//jumlah koleris
        $jumlah_melankolis = jumlah_data_latih($db_object, " WHERE kelas_asli='Melankolis'");//jumlah melankolis
        $jumlah_plegmatis = jumlah_data_latih($db_object, " WHERE kelas_asli='Plegmatis'");//jumlah plegmatis

	$p_sanguin = $jumlah_sanguin/$jumlah_data;
	$p_koleris = $jumlah_koleris/$jumlah_data;
        $p_melankolis = $jumlah_melankolis/$jumlah_data;
        $p_plegmatis = $jumlah_plegmatis/$jumlah_data;

	//jumlah atribut jenis kelamin
	$jumlah_jenis_kelamin_laki_sanguin = jumlah_data_latih($db_object, " WHERE jenis_kelamin='L' AND kelas_asli='Sanguin'");
	$jumlah_jenis_kelamin_laki_koleris = jumlah_data_latih($db_object, " WHERE jenis_kelamin='L' AND kelas_asli='Koleris'");
        $jumlah_jenis_kelamin_laki_melankolis = jumlah_data_latih($db_object, " WHERE jenis_kelamin='L' AND kelas_asli='Melankolis'");
        $jumlah_jenis_kelamin_laki_plegmatis = jumlah_data_latih($db_object, " WHERE jenis_kelamin='L' AND kelas_asli='Plegmatis'");
        
	$jumlah_jenis_kelamin_perempuan_sanguin = jumlah_data_latih($db_object, " WHERE jenis_kelamin='P' AND kelas_asli='Sanguin'");
	$jumlah_jenis_kelamin_perempuan_koleris = jumlah_data_latih($db_object, " WHERE jenis_kelamin='P' AND kelas_asli='Koleris'");
        $jumlah_jenis_kelamin_perempuan_melankolis = jumlah_data_latih($db_object, " WHERE jenis_kelamin='P' AND kelas_asli='Melankolis'");
        $jumlah_jenis_kelamin_perempuan_plegmatis = jumlah_data_latih($db_object, " WHERE jenis_kelamin='P' AND kelas_asli='Plegmatis'");
        
	//probabilitas atribut jenis_kelamin
	$p_jenis_kelamin_laki_sanguin = $jumlah_jenis_kelamin_laki_sanguin/$jumlah_sanguin;
	$p_jenis_kelamin_laki_koleris = $jumlah_jenis_kelamin_laki_koleris/$jumlah_koleris;
        $p_jenis_kelamin_laki_melankolis = $jumlah_jenis_kelamin_laki_melankolis/$jumlah_melankolis;
        $p_jenis_kelamin_laki_plegmatis = $jumlah_jenis_kelamin_laki_plegmatis/$jumlah_plegmatis;
        
	$p_jenis_kelamin_perempuan_sanguin = $jumlah_jenis_kelamin_perempuan_sanguin/$jumlah_sanguin;
	$p_jenis_kelamin_perempuan_koleris = $jumlah_jenis_kelamin_perempuan_koleris/$jumlah_koleris;
        $p_jenis_kelamin_perempuan_melankolis = $jumlah_jenis_kelamin_perempuan_melankolis/$jumlah_melankolis;
        $p_jenis_kelamin_perempuan_plegmatis = $jumlah_jenis_kelamin_perempuan_plegmatis/$jumlah_plegmatis;
        
	//display table probabilitas jenis_kelamin
        if($show_perhitungan){
	echo "<table class='table table-bordered table-striped  table-hover' style='width:40%'>";
		echo "<tr>";
			echo "<td><b><u>Jenis Kelamin:</u></b></td>";
			echo "<td>Sanguin</td>";
			echo "<td>Koleris</td>";
                        echo "<td>Melankolis</td>";
                        echo "<td>Plegmatis</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Laki-laki</td>";
			echo "<td>".number_format($p_jenis_kelamin_laki_sanguin, dec())."</td>";
			echo "<td>".number_format($p_jenis_kelamin_laki_koleris, dec())."</td>";
                        echo "<td>".number_format($p_jenis_kelamin_laki_melankolis, dec())."</td>";
                        echo "<td>".number_format($p_jenis_kelamin_laki_plegmatis, dec())."</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Perempuan</td>";
			echo "<td>".number_format($p_jenis_kelamin_perempuan_sanguin, dec())."</td>";
			echo "<td>".number_format($p_jenis_kelamin_perempuan_koleris, dec())."</td>";
                        echo "<td>".number_format($p_jenis_kelamin_perempuan_melankolis, dec())."</td>";
                        echo "<td>".number_format($p_jenis_kelamin_perempuan_plegmatis, dec())."</td>";
		echo "</tr>";
	echo "</table>";

	echo "<br>";
        }
	//------------------------------------------------------------------------------
	//jumlah atribut sekolah
	$jumlah_sekolah_swasta_sanguin = jumlah_data_latih($db_object, " WHERE sekolah='Swasta' AND kelas_asli='Sanguin'");
        $jumlah_sekolah_swasta_koleris = jumlah_data_latih($db_object, " WHERE sekolah='Swasta' AND kelas_asli='Koleris'");
        $jumlah_sekolah_swasta_melankolis = jumlah_data_latih($db_object, " WHERE sekolah='Swasta' AND kelas_asli='Melankolis'");
        $jumlah_sekolah_swasta_plegmatis = jumlah_data_latih($db_object, " WHERE sekolah='Swasta' AND kelas_asli='Plegmatis'");
        
        $jumlah_sekolah_negeri_sanguin = jumlah_data_latih($db_object, " WHERE sekolah='Negeri' AND kelas_asli='Sanguin'");
	$jumlah_sekolah_negeri_koleris = jumlah_data_latih($db_object, " WHERE sekolah='Negeri' AND kelas_asli='Koleris'");
        $jumlah_sekolah_negeri_melankolis = jumlah_data_latih($db_object, " WHERE sekolah='Negeri' AND kelas_asli='Melankolis'");
        $jumlah_sekolah_negeri_plegmatis = jumlah_data_latih($db_object, " WHERE sekolah='Negeri' AND kelas_asli='Plegmatis'");
        
	//probabilitas atribut sekolah
	$p_sekolah_swasta_sanguin = $jumlah_sekolah_swasta_sanguin/$jumlah_sanguin;
	$p_sekolah_swasta_koleris = $jumlah_sekolah_swasta_koleris/$jumlah_koleris;
        $p_sekolah_swasta_melankolis = $jumlah_sekolah_swasta_melankolis/$jumlah_melankolis;
        $p_sekolah_swasta_plegmatis = $jumlah_sekolah_swasta_plegmatis/$jumlah_plegmatis;
        
	$p_sekolah_negeri_sanguin = $jumlah_sekolah_negeri_sanguin/$jumlah_sanguin;
	$p_sekolah_negeri_koleris = $jumlah_sekolah_negeri_koleris/$jumlah_koleris;
	$p_sekolah_negeri_melankolis = $jumlah_sekolah_negeri_melankolis/$jumlah_melankolis;
	$p_sekolah_negeri_plegmatis = $jumlah_sekolah_negeri_plegmatis/$jumlah_plegmatis;
	//display table probabilitas sekolah
        if($show_perhitungan){
	echo "<table class='table table-bordered table-striped  table-hover' style='width:40%'>";
		echo "<tr>";
			echo "<td><b><u>Asal Sekolah:</u></b></td>";
			echo "<td>Sanguin</td>";
			echo "<td>Koleris</td>";
                        echo "<td>Melankolis</td>";
                        echo "<td>Plegmatis</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Swasta</td>";
			echo "<td>".number_format($p_sekolah_swasta_sanguin, dec())."</td>";
			echo "<td>".number_format($p_sekolah_swasta_koleris, dec())."</td>";
                        echo "<td>".number_format($p_sekolah_swasta_melankolis, dec())."</td>";
                        echo "<td>".number_format($p_sekolah_swasta_plegmatis, dec())."</td>";
		echo "</tr>";
		echo "<tr>";
			echo "<td>Negeri</td>";
			echo "<td>".number_format($p_sekolah_negeri_sanguin, dec())."</td>";
			echo "<td>".number_format($p_sekolah_negeri_koleris, dec())."</td>";
                        echo "<td>".number_format($p_sekolah_negeri_melankolis, dec())."</td>";
                        echo "<td>".number_format($p_sekolah_negeri_plegmatis, dec())."</td>";
		echo "</tr>";
	echo "</table>";
        }

	//xusia sanguin
	$jumlah_usia_sanguin = get_jumlah_sum_atribut($db_object, "usia", "Sanguin");
	$x_usia_sanguin = $jumlah_usia_sanguin/$jumlah_sanguin;
	//xusia  koleris
	$jumlah_usia_koleris = get_jumlah_sum_atribut($db_object, "usia", "Koleris");
	$x_usia_koleris = $jumlah_usia_koleris/$jumlah_koleris;
        //xusia  melankolis
	$jumlah_usia_melankolis = get_jumlah_sum_atribut($db_object, "usia", "Melankolis");
	$x_usia_melankolis = $jumlah_usia_melankolis/$jumlah_melankolis;
        //xusia  plegmatis
	$jumlah_usia_plegmatis = get_jumlah_sum_atribut($db_object, "usia", "Plegmatis");
	$x_usia_plegmatis = $jumlah_usia_plegmatis/$jumlah_plegmatis;
        
        if($show_perhitungan){
        echo "<br>";
        echo "<strong><u>Atribut Usia:<br></u></strong>";
	echo "X Usia Sanguin=".number_format($x_usia_sanguin, dec())."<br>";
	echo "X Usia Koleris=".number_format($x_usia_koleris, dec())."<br>";
        echo "X Usia Melankolis=".number_format($x_usia_melankolis, dec())."<br>";
        echo "X Usia Plegmatis=".number_format($x_usia_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S2usia Sanguin
	$s2_usia_sanguin = get_s2_populasi($db_object, 'usia', 'Sanguin', $x_usia_sanguin, $jumlah_sanguin);
	//S2usia Koleris
	$s2_usia_koleris = get_s2_populasi($db_object, 'usia', 'Koleris', $x_usia_koleris, $jumlah_koleris);
        //S2usia Melankolis
	$s2_usia_melankolis = get_s2_populasi($db_object, 'usia', 'Melankolis', $x_usia_melankolis, $jumlah_melankolis);
        //S2usia Plegmatis
	$s2_usia_plegmatis = get_s2_populasi($db_object, 'usia', 'Plegmatis', $x_usia_plegmatis, $jumlah_plegmatis);
        if($show_perhitungan){
	echo "S2 Usia Sanguin=".number_format($s2_usia_sanguin, dec())."<br>";
	echo "S2 Usia Koleris=".number_format($s2_usia_koleris, dec())."<br>";
        echo "S2 Usia Melankolis=".number_format($s2_usia_melankolis, dec())."<br>";
        echo "S2 Usia Plegmatis=".number_format($s2_usia_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S usia Sanguin
	$s_usia_sanguin = sqrt($s2_usia_sanguin);
	//S usia Koleris
	$s_usia_koleris = sqrt($s2_usia_koleris);
        //S usia Melankolis
	$s_usia_melankolis = sqrt($s2_usia_melankolis);
        //S usia Plegmatis
	$s_usia_plegmatis = sqrt($s2_usia_plegmatis);
        
        //s2 ^2 usia sanguin
        $s2_pangkat2_usia_sanguin = pow($s2_usia_sanguin, 2);
        //s2 ^2 usia koleris
        $s2_pangkat2_usia_koleris = pow($s2_usia_koleris, 2);
        //s2 ^2 usia melankolis
        $s2_pangkat2_usia_melankolis = pow($s2_usia_melankolis, 2);
        //s2 ^2 usia plegmatis
        $s2_pangkat2_usia_plegmatis = pow($s2_usia_plegmatis, 2);
        
        if($show_perhitungan){
	echo "S Usia Sanguin=".number_format($s_usia_sanguin, dec())."<br>";
	echo "S Usia Koleris=".number_format($s_usia_koleris, dec())."<br>";
        echo "S Usia Melankolis=".number_format($s_usia_melankolis, dec())."<br>";
        echo "S Usia Plegmatis=".number_format($s_usia_plegmatis, dec())."<br>";
        }
        //======================================================================
        //jawaban_a
        //x jawaban_a sanguin
	$jumlah_jawaban_a_sanguin = get_jumlah_sum_atribut($db_object, "jawaban_a", "Sanguin");
	$x_jawaban_a_sanguin = $jumlah_jawaban_a_sanguin/$jumlah_sanguin;
	//x jawaban_a  koleris
	$jumlah_jawaban_a_koleris = get_jumlah_sum_atribut($db_object, "jawaban_a", "Koleris");
	$x_jawaban_a_koleris = $jumlah_jawaban_a_koleris/$jumlah_koleris;
        //x jawaban_a  melankolis
	$jumlah_jawaban_a_melankolis = get_jumlah_sum_atribut($db_object, "jawaban_a", "Melankolis");
	$x_jawaban_a_melankolis = $jumlah_jawaban_a_melankolis/$jumlah_melankolis;
        //x jawaban_a  plegmatis
	$jumlah_jawaban_a_plegmatis = get_jumlah_sum_atribut($db_object, "jawaban_a", "Plegmatis");
	$x_jawaban_a_plegmatis = $jumlah_jawaban_a_plegmatis/$jumlah_plegmatis;
        if($show_perhitungan){
        echo "<br>";
        echo "<strong><u>Atribut Jawaban A:<br></u></strong>";
	echo "X Jawaban A Sanguin=".number_format($x_jawaban_a_sanguin, dec())."<br>";
	echo "X Jawaban A Koleris=".number_format($x_jawaban_a_koleris, dec())."<br>";
        echo "X Jawaban A Melankolis=".number_format($x_jawaban_a_melankolis, dec())."<br>";
        echo "X Jawaban A Plegmatis=".number_format($x_jawaban_a_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S2 jawaban_a Sanguin
	$s2_jawaban_a_sanguin = get_s2_populasi($db_object, 'jawaban_a', 'Sanguin', $x_jawaban_a_sanguin, $jumlah_sanguin);
	//S2 jawaban_a Koleris
	$s2_jawaban_a_koleris = get_s2_populasi($db_object, 'jawaban_a', 'Koleris', $x_jawaban_a_koleris, $jumlah_koleris);
        //S2 jawaban_a Melankolis
	$s2_jawaban_a_melankolis = get_s2_populasi($db_object, 'jawaban_a', 'Melankolis', $x_jawaban_a_melankolis, $jumlah_melankolis);
        //S2 jawaban_a Koleris
	$s2_jawaban_a_plegmatis = get_s2_populasi($db_object, 'jawaban_a', 'Plegmatis', $x_jawaban_a_plegmatis, $jumlah_plegmatis);
        if($show_perhitungan){
	echo "S2 Jawaban A Sanguin=".number_format($s2_jawaban_a_sanguin, dec())."<br>";
	echo "S2 Jawaban A Koleris=".number_format($s2_jawaban_a_koleris, dec())."<br>";
        echo "S2 Jawaban A Melankolis=".number_format($s2_jawaban_a_melankolis, dec())."<br>";
        echo "S2 Jawaban A Plegmatis=".number_format($s2_jawaban_a_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S jawaban_a Sanguin
	$s_jawaban_a_sanguin = sqrt($s2_jawaban_a_sanguin);
	//S jawaban_a Koleris
	$s_jawaban_a_koleris = sqrt($s2_jawaban_a_koleris);
        //S jawaban_a Melankolis
	$s_jawaban_a_melankolis = sqrt($s2_jawaban_a_melankolis);
        //S jawaban_a Plegmatis
	$s_jawaban_a_plegmatis = sqrt($s2_jawaban_a_plegmatis);
        if($show_perhitungan){
	echo "S Jawaban A Sanguin=".number_format($s_jawaban_a_sanguin, dec())."<br>";
	echo "S Jawaban A Koleris=".number_format($s_jawaban_a_koleris, dec())."<br>";
        echo "S Jawaban A Melankolis=".number_format($s_jawaban_a_melankolis, dec())."<br>";
        echo "S Jawaban A Plegmatis=".number_format($s_jawaban_a_plegmatis, dec())."<br>";
        }
        //s2 ^2 jawaban_a sanguin
        $s2_pangkat2_jawaban_a_sanguin = pow($s2_jawaban_a_sanguin, 2);
        //s2 ^2 jawaban_a koleris
        $s2_pangkat2_jawaban_a_koleris = pow($s2_jawaban_a_koleris, 2);
        //s2 ^2 jawaban_a melankolis
        $s2_pangkat2_jawaban_a_melankolis = pow($s2_jawaban_a_melankolis, 2);
        //s2 ^2 jawaban_a plegmatis
        $s2_pangkat2_jawaban_a_plegmatis = pow($s2_jawaban_a_plegmatis, 2);
        
        //==================================================
        //jawaban_b
        //x jawaban_b sanguin
	$jumlah_jawaban_b_sanguin = get_jumlah_sum_atribut($db_object, "jawaban_b", "Sanguin");
	$x_jawaban_b_sanguin = $jumlah_jawaban_b_sanguin/$jumlah_sanguin;
	//x jawaban_b  koleris
	$jumlah_jawaban_b_koleris = get_jumlah_sum_atribut($db_object, "jawaban_b", "Koleris");
	$x_jawaban_b_koleris = $jumlah_jawaban_b_koleris/$jumlah_koleris;
        //x jawaban_b  melankolis
	$jumlah_jawaban_b_melankolis = get_jumlah_sum_atribut($db_object, "jawaban_b", "Melankolis");
	$x_jawaban_b_melankolis = $jumlah_jawaban_b_melankolis/$jumlah_melankolis;
        //x jawaban_b  plegmatis
	$jumlah_jawaban_b_plegmatis = get_jumlah_sum_atribut($db_object, "jawaban_b", "Plegmatis");
	$x_jawaban_b_plegmatis = $jumlah_jawaban_b_plegmatis/$jumlah_plegmatis;
        if($show_perhitungan){
        echo "<br>";
        echo "<strong><u>Atribut Jawaban B:<br></u></strong>";
	echo "X Jawaban B Sanguin=".number_format($x_jawaban_b_sanguin, dec())."<br>";
	echo "X Jawaban B Koleris=".number_format($x_jawaban_b_koleris, dec())."<br>";
        echo "X Jawaban B Melankolis=".number_format($x_jawaban_b_melankolis, dec())."<br>";
        echo "X Jawaban B Plegmatis=".number_format($x_jawaban_b_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S2 jawaban_b Sanguin
	$s2_jawaban_b_sanguin = get_s2_populasi($db_object, 'jawaban_b', 'Sanguin', $x_jawaban_b_sanguin, $jumlah_sanguin);
	//S2 jawaban_b Koleris
	$s2_jawaban_b_koleris = get_s2_populasi($db_object, 'jawaban_b', 'Koleris', $x_jawaban_b_koleris, $jumlah_koleris);
        //S2 jawaban_b Melankolis
	$s2_jawaban_b_melankolis = get_s2_populasi($db_object, 'jawaban_b', 'Melankolis', $x_jawaban_b_melankolis, $jumlah_melankolis);
        //S2 jawaban_b Koleris
	$s2_jawaban_b_plegmatis = get_s2_populasi($db_object, 'jawaban_b', 'Plegmatis', $x_jawaban_b_plegmatis, $jumlah_plegmatis);
        if($show_perhitungan){
	echo "S2 Jawaban B Sanguin=".number_format($s2_jawaban_b_sanguin, dec())."<br>";
	echo "S2 Jawaban B Koleris=".number_format($s2_jawaban_b_koleris, dec())."<br>";
        echo "S2 Jawaban B Melankolis=".number_format($s2_jawaban_b_melankolis, dec())."<br>";
        echo "S2 Jawaban B Plegmatis=".number_format($s2_jawaban_b_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S jawaban_b Sanguin
	$s_jawaban_b_sanguin = sqrt($s2_jawaban_b_sanguin);
	//S jawaban_b Koleris
	$s_jawaban_b_koleris = sqrt($s2_jawaban_b_koleris);
        //S jawaban_b Melankolis
	$s_jawaban_b_melankolis = sqrt($s2_jawaban_b_melankolis);
        //S jawaban_b Plegmatis
	$s_jawaban_b_plegmatis = sqrt($s2_jawaban_b_plegmatis);
        if($show_perhitungan){
	echo "S Jawaban B Sanguin=".number_format($s_jawaban_b_sanguin, dec())."<br>";
	echo "S Jawaban B Koleris=".number_format($s_jawaban_b_koleris, dec())."<br>";
        echo "S Jawaban B Melankolis=".number_format($s_jawaban_b_melankolis, dec())."<br>";
        echo "S Jawaban B Plegmatis=".number_format($s_jawaban_b_plegmatis, dec())."<br>";
        }
        
        //s2 ^2 jawaban_b sanguin
        $s2_pangkat2_jawaban_b_sanguin = pow($s2_jawaban_b_sanguin, 2);
        //s2 ^2 jawaban_b koleris
        $s2_pangkat2_jawaban_b_koleris = pow($s2_jawaban_b_koleris, 2);
        //s2 ^2 jawaban_b melankolis
        $s2_pangkat2_jawaban_b_melankolis = pow($s2_jawaban_b_melankolis, 2);
        //s2 ^2 jawaban_b plegmatis
        $s2_pangkat2_jawaban_b_plegmatis = pow($s2_jawaban_b_plegmatis, 2);
        //========================================================
        //jawaban_c
        //x jawaban_c sanguin
	$jumlah_jawaban_c_sanguin = get_jumlah_sum_atribut($db_object, "jawaban_c", "Sanguin");
	$x_jawaban_c_sanguin = $jumlah_jawaban_c_sanguin/$jumlah_sanguin;
	//x jawaban_c  koleris
	$jumlah_jawaban_c_koleris = get_jumlah_sum_atribut($db_object, "jawaban_c", "Koleris");
	$x_jawaban_c_koleris = $jumlah_jawaban_c_koleris/$jumlah_koleris;
        //x jawaban_c  melankolis
	$jumlah_jawaban_c_melankolis = get_jumlah_sum_atribut($db_object, "jawaban_c", "Melankolis");
	$x_jawaban_c_melankolis = $jumlah_jawaban_c_melankolis/$jumlah_melankolis;
        //x jawaban_c  plegmatis
	$jumlah_jawaban_c_plegmatis = get_jumlah_sum_atribut($db_object, "jawaban_c", "Plegmatis");
	$x_jawaban_c_plegmatis = $jumlah_jawaban_c_plegmatis/$jumlah_plegmatis;
        if($show_perhitungan){
        echo "<br>";
        echo "<strong><u>Atribut Jawaban C:<br></u></strong>";
	echo "X Jawaban C Sanguin=".number_format($x_jawaban_c_sanguin, dec())."<br>";
	echo "X Jawaban C Koleris=".number_format($x_jawaban_c_koleris, dec())."<br>";
        echo "X Jawaban C Melankolis=".number_format($x_jawaban_c_melankolis, dec())."<br>";
        echo "X Jawaban C Plegmatis=".number_format($x_jawaban_c_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S2 jawaban_c Sanguin
	$s2_jawaban_c_sanguin = get_s2_populasi($db_object, 'jawaban_c', 'Sanguin', $x_jawaban_c_sanguin, $jumlah_sanguin);
	//S2 jawaban_c Koleris
	$s2_jawaban_c_koleris = get_s2_populasi($db_object, 'jawaban_c', 'Koleris', $x_jawaban_c_koleris, $jumlah_koleris);
        //S2 jawaban_c Melankolis
	$s2_jawaban_c_melankolis = get_s2_populasi($db_object, 'jawaban_c', 'Melankolis', $x_jawaban_c_melankolis, $jumlah_melankolis);
        //S2 jawaban_c Koleris
	$s2_jawaban_c_plegmatis = get_s2_populasi($db_object, 'jawaban_c', 'Plegmatis', $x_jawaban_c_plegmatis, $jumlah_plegmatis);
        if($show_perhitungan){
	echo "S2 Jawaban C Sanguin=".number_format($s2_jawaban_c_sanguin, dec())."<br>";
	echo "S2 Jawaban C Koleris=".number_format($s2_jawaban_c_koleris, dec())."<br>";
        echo "S2 Jawaban C Melankolis=".number_format($s2_jawaban_c_melankolis, dec())."<br>";
        echo "S2 Jawaban C Plegmatis=".number_format($s2_jawaban_c_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S jawaban_c Sanguin
	$s_jawaban_c_sanguin = sqrt($s2_jawaban_c_sanguin);
	//S jawaban_c Koleris
	$s_jawaban_c_koleris = sqrt($s2_jawaban_c_koleris);
        //S jawaban_c Melankolis
	$s_jawaban_c_melankolis = sqrt($s2_jawaban_c_melankolis);
        //S jawaban_c Plegmatis
	$s_jawaban_c_plegmatis = sqrt($s2_jawaban_c_plegmatis);
        if($show_perhitungan){
	echo "S Jawaban C Sanguin=".number_format($s_jawaban_c_sanguin, dec())."<br>";
	echo "S Jawaban C Koleris=".number_format($s_jawaban_c_koleris, dec())."<br>";
        echo "S Jawaban C Melankolis=".number_format($s_jawaban_c_melankolis, dec())."<br>";
        echo "S Jawaban C Plegmatis=".number_format($s_jawaban_c_plegmatis, dec())."<br>";
        }
        
        //s2 ^2 jawaban_c sanguin
        $s2_pangkat2_jawaban_c_sanguin = pow($s2_jawaban_c_sanguin, 2);
        //s2 ^2 jawaban_c koleris
        $s2_pangkat2_jawaban_c_koleris = pow($s2_jawaban_c_koleris, 2);
        //s2 ^2 jawaban_c melankolis
        $s2_pangkat2_jawaban_c_melankolis = pow($s2_jawaban_c_melankolis, 2);
        //s2 ^2 jawaban_c plegmatis
        $s2_pangkat2_jawaban_c_plegmatis = pow($s2_jawaban_c_plegmatis, 2);
        //===============================================================
        //x jawaban_d sanguin
	$jumlah_jawaban_d_sanguin = get_jumlah_sum_atribut($db_object, "jawaban_d", "Sanguin");
	$x_jawaban_d_sanguin = $jumlah_jawaban_d_sanguin/$jumlah_sanguin;
	//x jawaban_d  koleris
	$jumlah_jawaban_d_koleris = get_jumlah_sum_atribut($db_object, "jawaban_d", "Koleris");
	$x_jawaban_d_koleris = $jumlah_jawaban_d_koleris/$jumlah_koleris;
        //x jawaban_d  melankolis
	$jumlah_jawaban_d_melankolis = get_jumlah_sum_atribut($db_object, "jawaban_d", "Melankolis");
	$x_jawaban_d_melankolis = $jumlah_jawaban_d_melankolis/$jumlah_melankolis;
        //x jawaban_d  plegmatis
	$jumlah_jawaban_d_plegmatis = get_jumlah_sum_atribut($db_object, "jawaban_d", "Plegmatis");
	$x_jawaban_d_plegmatis = $jumlah_jawaban_d_plegmatis/$jumlah_plegmatis;
        if($show_perhitungan){
        echo "<br>";
        echo "<strong><u>Atribut Jawaban D:<br></u></strong>";
	echo "X Jawaban D Sanguin=".number_format($x_jawaban_d_sanguin, dec())."<br>";
	echo "X Jawaban D Koleris=".number_format($x_jawaban_d_koleris, dec())."<br>";
        echo "X Jawaban D Melankolis=".number_format($x_jawaban_d_melankolis, dec())."<br>";
        echo "X Jawaban D Plegmatis=".number_format($x_jawaban_d_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S2 jawaban_d Sanguin
	$s2_jawaban_d_sanguin = get_s2_populasi($db_object, 'jawaban_d', 'Sanguin', $x_jawaban_d_sanguin, $jumlah_sanguin);
	//S2 jawaban_d Koleris
	$s2_jawaban_d_koleris = get_s2_populasi($db_object, 'jawaban_d', 'Koleris', $x_jawaban_d_koleris, $jumlah_koleris);
        //S2 jawaban_d Melankolis
	$s2_jawaban_d_melankolis = get_s2_populasi($db_object, 'jawaban_d', 'Melankolis', $x_jawaban_d_melankolis, $jumlah_melankolis);
        //S2 jawaban_d Koleris
	$s2_jawaban_d_plegmatis = get_s2_populasi($db_object, 'jawaban_d', 'Plegmatis', $x_jawaban_d_plegmatis, $jumlah_plegmatis);
        if($show_perhitungan){
	echo "S2 Jawaban D Sanguin=".number_format($s2_jawaban_d_sanguin, dec())."<br>";
	echo "S2 Jawaban D Koleris=".number_format($s2_jawaban_d_koleris, dec())."<br>";
        echo "S2 Jawaban D Melankolis=".number_format($s2_jawaban_d_melankolis, dec())."<br>";
        echo "S2 Jawaban D Plegmatis=".number_format($s2_jawaban_d_plegmatis, dec())."<br>";
	echo "<br>";
        }
	//S jawaban_d Sanguin
	$s_jawaban_d_sanguin = sqrt($s2_jawaban_d_sanguin);
	//S jawaban_d Koleris
	$s_jawaban_d_koleris = sqrt($s2_jawaban_d_koleris);
        //S jawaban_d Melankolis
	$s_jawaban_d_melankolis = sqrt($s2_jawaban_d_melankolis);
        //S jawaban_d Plegmatis
	$s_jawaban_d_plegmatis = sqrt($s2_jawaban_d_plegmatis);
        if($show_perhitungan){
	echo "S Jawaban D Sanguin=".number_format($s_jawaban_d_sanguin, dec())."<br>";
	echo "S Jawaban D Koleris=".number_format($s_jawaban_d_koleris, dec())."<br>";
        echo "S Jawaban D Melankolis=".number_format($s_jawaban_d_melankolis, dec())."<br>";
        echo "S Jawaban D Plegmatis=".number_format($s_jawaban_d_plegmatis, dec())."<br>";
        }
        
        //s2 ^2 jawaban_d sanguin
        $s2_pangkat2_jawaban_d_sanguin = pow($s2_jawaban_d_sanguin, 2);
        //s2 ^2 jawaban_d koleris
        $s2_pangkat2_jawaban_d_koleris = pow($s2_jawaban_d_koleris, 2);
        //s2 ^2 jawaban_d melankolis
        $s2_pangkat2_jawaban_d_melankolis = pow($s2_jawaban_d_melankolis, 2);
        //s2 ^2 jawaban_d plegmatis
        $s2_pangkat2_jawaban_d_plegmatis = pow($s2_jawaban_d_plegmatis, 2);
        //======================================================================
        //#HITUNG PROBABILITAS DENGAN DATA UJI
        if($show_perhitungan){
        echo "<strong><h3>Probabilitas<br></h3></strong>";
        }
	$dua_phi = (2*3.14);
        //#usia
        //sanguin
	$depan_usia_sanguin = sqrt($dua_phi*$s2_usia_sanguin);
	$belakang_usia_sanguin = exp( ((pow($usia-$x_usia_sanguin,2)) / (2*$s2_pangkat2_usia_sanguin)));
	$prob_usia_sanguin = 1/($depan_usia_sanguin * $belakang_usia_sanguin);
        //koleris
	$depan_usia_koleris = sqrt($dua_phi*$s2_usia_koleris);
	$belakang_usia_koleris = exp( ((pow($usia-$x_usia_koleris,2)) / (2*$s2_pangkat2_usia_koleris)));
	$prob_usia_koleris = 1/($depan_usia_koleris * $belakang_usia_koleris);
        //melankolis
	$depan_usia_melankolis = sqrt($dua_phi*$s2_usia_melankolis);
	$belakang_usia_melankolis = exp( ((pow($usia-$x_usia_melankolis,2)) / (2*$s2_pangkat2_usia_melankolis)));
	$prob_usia_melankolis = 1/($depan_usia_melankolis * $belakang_usia_melankolis);
        //plegmatis
	$depan_usia_plegmatis = sqrt($dua_phi*$s2_usia_plegmatis);
	$belakang_usia_plegmatis = exp( ((pow($usia-$x_usia_plegmatis,2)) / (2*$s2_pangkat2_usia_plegmatis)));
	$prob_usia_plegmatis = 1/($depan_usia_plegmatis * $belakang_usia_plegmatis);
        //display
        if($show_perhitungan){
	echo "<br>";
//	echo "P(usia|Sanguin)=".number_format($prob_usia_sanguin, dec())."<br>";
//	echo "P(usia|Koleris)=".number_format($prob_usia_koleris, dec())."<br>";
//        echo "P(usia|Melankolis)=".number_format($prob_usia_melankolis, dec())."<br>";
//        echo "P(usia|Plegmatis)=".number_format($prob_usia_plegmatis, dec())."<br>";
        echo "P(usia|Sanguin)=".($prob_usia_sanguin)."<br>";
	echo "P(usia|Koleris)=".($prob_usia_koleris)."<br>";
        echo "P(usia|Melankolis)=".($prob_usia_melankolis)."<br>";
        echo "P(usia|Plegmatis)=".($prob_usia_plegmatis)."<br>";
        
        }
	//probablitas jenis_kelamin
	$prob_jenis_kelamin_sanguin = get_jumlah_atribut($db_object, "jenis_kelamin", $jenis_kelamin, "Sanguin") / $jumlah_sanguin;
	$prob_jenis_kelamin_koleris = get_jumlah_atribut($db_object, "jenis_kelamin", $jenis_kelamin, "Koleris") / $jumlah_koleris;
        $prob_jenis_kelamin_melankolis = get_jumlah_atribut($db_object, "jenis_kelamin", $jenis_kelamin, "Melankolis") / $jumlah_melankolis;
        $prob_jenis_kelamin_plegmatis = get_jumlah_atribut($db_object, "jenis_kelamin", $jenis_kelamin, "Plegmatis") / $jumlah_plegmatis;
	if($show_perhitungan){
        echo "<br>";
	echo "P(jenis_kelamin|Sanguin)=".number_format($prob_jenis_kelamin_sanguin, dec())."<br>";
	echo "P(jenis_kelamin|Koleris)=".number_format($prob_jenis_kelamin_koleris, dec())."<br>";
        echo "P(jenis_kelamin|Melankolis)=".number_format($prob_jenis_kelamin_melankolis, dec())."<br>";
        echo "P(jenis_kelamin|Plegmatis)=".number_format($prob_jenis_kelamin_plegmatis, dec())."<br>";
        }
	//probablitas sekolah
	$prob_sekolah_sanguin = get_jumlah_atribut($db_object, "sekolah", $sekolah, "Sanguin") / $jumlah_sanguin;
	$prob_sekolah_koleris = get_jumlah_atribut($db_object, "sekolah", $sekolah, "Koleris") / $jumlah_koleris;
        $prob_sekolah_melankolis = get_jumlah_atribut($db_object, "sekolah", $sekolah, "Melankolis") / $jumlah_melankolis;
        $prob_sekolah_plegmatis = get_jumlah_atribut($db_object, "sekolah", $sekolah, "Plegmatis") / $jumlah_plegmatis;
	if($show_perhitungan){
        echo "<br>";
	echo "P(Asal Sekolah|Sanguin)=".number_format($prob_sekolah_sanguin, dec())."<br>";
	echo "P(Asal Sekolah|Koleris)=".number_format($prob_sekolah_koleris, dec())."<br>";
        echo "P(Asal Sekolah|Melankolis)=".number_format($prob_sekolah_melankolis, dec())."<br>";
        echo "P(Asal Sekolah|Plegmatis)=".number_format($prob_sekolah_plegmatis, dec())."<br>";
        }
        
        //#jawaban_a
        //sanguin
//	$depan_usia_sanguin = sqrt($dua_phi*$s2_usia_sanguin);
//	$belakang_usia_sanguin = exp( ((pow($usia-$x_usia_sanguin,2)) / (2*$s2_pangkat2_usia_sanguin)));
//	$prob_usia_sanguin = 1/($depan_usia_sanguin * $belakang_usia_sanguin);
        //sanguin
	$depan_jawaban_a_sanguin = sqrt($dua_phi*$s2_jawaban_a_sanguin);
	$belakang_jawaban_a_sanguin = exp( ((pow($jawaban_a-$x_jawaban_a_sanguin,2)) / (2*$s2_pangkat2_jawaban_a_sanguin)));
	$prob_jawaban_a_sanguin = 1/($depan_jawaban_a_sanguin * $belakang_jawaban_a_sanguin);
        //koleris
	$depan_jawaban_a_koleris = sqrt($dua_phi*$s2_jawaban_a_koleris);
	$belakang_jawaban_a_koleris = exp( ((pow($jawaban_a-$x_jawaban_a_koleris,2)) / (2*$s2_pangkat2_jawaban_a_koleris)));
	$prob_jawaban_a_koleris = 1/($depan_jawaban_a_koleris * $belakang_jawaban_a_koleris);
        //melankolis
	$depan_jawaban_a_melankolis = sqrt($dua_phi*$s2_jawaban_a_melankolis);
	$belakang_jawaban_a_melankolis = exp( ((pow($jawaban_a-$x_jawaban_a_melankolis,2)) / (2*$s2_pangkat2_jawaban_a_melankolis)));
	$prob_jawaban_a_melankolis = 1/($depan_jawaban_a_melankolis * $belakang_jawaban_a_melankolis);
        //plegmatis
	$depan_jawaban_a_plegmatis = sqrt($dua_phi*$s2_jawaban_a_plegmatis);
	$belakang_jawaban_a_plegmatis = exp( ((pow($jawaban_a-$x_jawaban_a_plegmatis,2)) / (2*$s2_pangkat2_jawaban_a_plegmatis)));
	$prob_jawaban_a_plegmatis = 1/($depan_jawaban_a_plegmatis * $belakang_jawaban_a_plegmatis);
        //display
        if($show_perhitungan){
	echo "<br>";
	echo "P(jawaban_a|Sanguin)=".number_format($prob_jawaban_a_sanguin, dec())."<br>";
	echo "P(jawaban_a|Koleris)=".number_format($prob_jawaban_a_koleris, dec())."<br>";
        echo "P(jawaban_a|Melankolis)=".number_format($prob_jawaban_a_melankolis, dec())."<br>";
        echo "P(jawaban_a|Plegmatis)=".number_format($prob_jawaban_a_plegmatis, dec())."<br>";
        }
        //======================================================================
        //#jawaban_b
        //sanguin
	$depan_jawaban_b_sanguin = sqrt($dua_phi*$s2_jawaban_b_sanguin);
	$belakang_jawaban_b_sanguin = exp( ((pow($jawaban_b-$x_jawaban_b_sanguin,2)) / (2*$s2_pangkat2_jawaban_b_sanguin)));
	$prob_jawaban_b_sanguin = 1/($depan_jawaban_b_sanguin * $belakang_jawaban_b_sanguin);
        //koleris
	$depan_jawaban_b_koleris = sqrt($dua_phi*$s2_jawaban_b_koleris);
	$belakang_jawaban_b_koleris = exp( ((pow($jawaban_b-$x_jawaban_b_koleris,2)) / (2*$s2_pangkat2_jawaban_b_koleris)));
	$prob_jawaban_b_koleris = 1/($depan_jawaban_b_koleris * $belakang_jawaban_b_koleris);
        //melankolis
	$depan_jawaban_b_melankolis = sqrt($dua_phi*$s2_jawaban_b_melankolis);
	$belakang_jawaban_b_melankolis = exp( ((pow($jawaban_b-$x_jawaban_b_melankolis,2)) / (2*$s2_pangkat2_jawaban_b_melankolis)));
	$prob_jawaban_b_melankolis = 1/($depan_jawaban_b_melankolis * $belakang_jawaban_b_melankolis);
        //plegmatis
	$depan_jawaban_b_plegmatis = sqrt($dua_phi*$s2_jawaban_b_plegmatis);
	$belakang_jawaban_b_plegmatis = exp( ((pow($jawaban_b-$x_jawaban_b_plegmatis,2)) / (2*$s2_pangkat2_jawaban_b_plegmatis)));
	$prob_jawaban_b_plegmatis = 1/($depan_jawaban_b_plegmatis * $belakang_jawaban_b_plegmatis);
        //display
        if($show_perhitungan){
	echo "<br>";
	echo "P(jawaban_b|Sanguin)=".number_format($prob_jawaban_b_sanguin, dec())."<br>";
	echo "P(jawaban_b|Koleris)=".number_format($prob_jawaban_b_koleris, dec())."<br>";
        echo "P(jawaban_b|Melankolis)=".number_format($prob_jawaban_b_melankolis, dec())."<br>";
        echo "P(jawaban_b|Plegmatis)=".number_format($prob_jawaban_b_plegmatis, dec())."<br>";
        }
        //======================================================================
        //#jawaban_c
        //sanguin
	$depan_jawaban_c_sanguin = sqrt($dua_phi*$s2_jawaban_c_sanguin);
	$belakang_jawaban_c_sanguin = exp( ((pow($jawaban_c-$x_jawaban_c_sanguin,2)) / (2*$s2_pangkat2_jawaban_c_sanguin)));
	$prob_jawaban_c_sanguin = 1/($depan_jawaban_c_sanguin * $belakang_jawaban_c_sanguin);
        //koleris
	$depan_jawaban_c_koleris = sqrt($dua_phi*$s2_jawaban_c_koleris);
	$belakang_jawaban_c_koleris = exp( ((pow($jawaban_c-$x_jawaban_c_koleris,2)) / (2*$s2_pangkat2_jawaban_c_koleris)));
	$prob_jawaban_c_koleris = 1/($depan_jawaban_c_koleris * $belakang_jawaban_c_koleris);
        //melankolis
	$depan_jawaban_c_melankolis = sqrt($dua_phi*$s2_jawaban_c_melankolis);
	$belakang_jawaban_c_melankolis = exp( ((pow($jawaban_c-$x_jawaban_c_melankolis,2)) / (2*$s2_pangkat2_jawaban_c_melankolis)));
	$prob_jawaban_c_melankolis = 1/($depan_jawaban_c_melankolis * $belakang_jawaban_c_melankolis);
        //plegmatis
	$depan_jawaban_c_plegmatis = sqrt($dua_phi*$s2_jawaban_c_plegmatis);
	$belakang_jawaban_c_plegmatis = exp( ((pow($jawaban_c-$x_jawaban_c_plegmatis,2)) / (2*$s2_pangkat2_jawaban_c_plegmatis)));
	$prob_jawaban_c_plegmatis = 1/($depan_jawaban_c_plegmatis * $belakang_jawaban_c_plegmatis);
        //display
        if($show_perhitungan){
	echo "<br>";
	echo "P(jawaban_c|Sanguin)=".number_format($prob_jawaban_c_sanguin, dec())."<br>";
	echo "P(jawaban_c|Koleris)=".number_format($prob_jawaban_c_koleris, dec())."<br>";
        echo "P(jawaban_c|Melankolis)=".number_format($prob_jawaban_c_melankolis, dec())."<br>";
        echo "P(jawaban_c|Plegmatis)=".number_format($prob_jawaban_c_plegmatis, dec())."<br>";
        }
        //======================================================================
        //#jawaban_d
        //sanguin
        //        $depan_jawaban_a_plegmatis = sqrt($dua_phi*$s2_jawaban_a_plegmatis);
//	$belakang_jawaban_a_plegmatis = exp( ((pow($jawaban_a-$x_jawaban_a_plegmatis,2)) / (2*$s2_pangkat2_jawaban_a_plegmatis)));
//	$prob_jawaban_a_plegmatis = 1/($depan_jawaban_a_plegmatis * $belakang_jawaban_a_plegmatis);
	$depan_jawaban_d_sanguin = sqrt($dua_phi*$s2_jawaban_d_sanguin);
	$belakang_jawaban_d_sanguin = exp( ((pow($jawaban_d-$x_jawaban_d_sanguin,2)) / (2*$s2_pangkat2_jawaban_d_sanguin)));
	$prob_jawaban_d_sanguin = 1/($depan_jawaban_d_sanguin * $belakang_jawaban_d_sanguin);
        //koleris
	$depan_jawaban_d_koleris = sqrt($dua_phi*$s2_jawaban_d_koleris);
	$belakang_jawaban_d_koleris = exp( ((pow($jawaban_d-$x_jawaban_d_koleris,2)) / (2*$s2_pangkat2_jawaban_d_koleris)));
	$prob_jawaban_d_koleris = 1/($depan_jawaban_d_koleris * $belakang_jawaban_d_koleris);
        //melankolis
	$depan_jawaban_d_melankolis = sqrt($dua_phi*$s2_jawaban_d_melankolis);
	$belakang_jawaban_d_melankolis = exp( ((pow($jawaban_d-$x_jawaban_d_melankolis,2)) / (2*$s2_pangkat2_jawaban_d_melankolis)));
	$prob_jawaban_d_melankolis = 1/($depan_jawaban_d_melankolis * $belakang_jawaban_d_melankolis);
        //plegmatis
	$depan_jawaban_d_plegmatis = sqrt($dua_phi*$s2_jawaban_d_plegmatis);
	$belakang_jawaban_d_plegmatis = exp( ((pow($jawaban_d-$x_jawaban_d_plegmatis,2)) / (2*$s2_pangkat2_jawaban_d_plegmatis)));
	$prob_jawaban_d_plegmatis = 1/($depan_jawaban_d_plegmatis * $belakang_jawaban_d_plegmatis);
        //display
        if($show_perhitungan){
	echo "<br>";
	echo "P(jawaban_d|Sanguin)=".number_format($prob_jawaban_d_sanguin, dec())."<br>";
	echo "P(jawaban_d|Koleris)=".number_format($prob_jawaban_d_koleris, dec())."<br>";
        echo "P(jawaban_d|Melankolis)=".number_format($prob_jawaban_d_melankolis, dec())."<br>";
        echo "P(jawaban_d|Plegmatis)=".number_format($prob_jawaban_d_plegmatis, dec())."<br>";
        }
        //===============================
	$nilai_sanguin = $p_sanguin * $prob_jenis_kelamin_sanguin * $prob_sekolah_sanguin *
					$prob_usia_sanguin * $prob_jawaban_a_sanguin * $prob_jawaban_b_sanguin * 
                                        $prob_jawaban_c_sanguin * $prob_jawaban_d_sanguin;
        if($show_perhitungan){
	echo "<br>";
	echo "Nilai Sanguin = ".number_format($p_sanguin, dec())
                            ." x ".number_format($prob_jenis_kelamin_sanguin, dec())
                            ." x ".number_format($prob_sekolah_sanguin, dec())
                            ." x ".number_format($prob_usia_sanguin, dec())
                            ." x ".number_format($prob_jawaban_a_sanguin, dec())
                            ." x ".number_format($prob_jawaban_b_sanguin, dec())
                            ." x ".number_format($prob_jawaban_c_sanguin, dec())
                            ." x ".number_format($prob_jawaban_d_sanguin, dec())
                            ." = ".number_format($nilai_sanguin, 20);
        }
        //===============================
        $nilai_koleris = $p_koleris * $prob_jenis_kelamin_koleris * $prob_sekolah_koleris *
					$prob_usia_koleris * $prob_jawaban_a_koleris * $prob_jawaban_b_koleris * 
                                        $prob_jawaban_c_koleris * $prob_jawaban_d_koleris;
	if($show_perhitungan){
        echo "<br>";
	echo "Nilai Koleris = ".number_format($p_koleris, dec())
                            ." x ".number_format($prob_jenis_kelamin_koleris, dec())
                            ." x ".number_format($prob_sekolah_koleris, dec())
                            ." x ".number_format($prob_usia_koleris, dec())
                            ." x ".number_format($prob_jawaban_a_koleris, dec())
                            ." x ".number_format($prob_jawaban_b_koleris, dec())
                            ." x ".number_format($prob_jawaban_c_koleris, dec())
                            ." x ".number_format($prob_jawaban_d_koleris, dec())
                            ." = ".number_format($nilai_koleris, 20);
        }
        //===============================
        $nilai_melankolis = $p_melankolis * $prob_jenis_kelamin_melankolis * $prob_sekolah_melankolis *
					$prob_usia_melankolis * $prob_jawaban_a_melankolis * $prob_jawaban_b_melankolis * 
                                        $prob_jawaban_c_melankolis * $prob_jawaban_d_melankolis;
	if($show_perhitungan){
        echo "<br>";
	echo "Nilai Melankolis = ".number_format($p_melankolis, dec())
                            ." x ".number_format($prob_jenis_kelamin_melankolis, dec())
                            ." x ".number_format($prob_sekolah_melankolis, dec())
                            ." x ".number_format($prob_usia_melankolis, dec())
                            ." x ".number_format($prob_jawaban_a_melankolis, dec())
                            ." x ".number_format($prob_jawaban_b_melankolis, dec())
                            ." x ".number_format($prob_jawaban_c_melankolis, dec())
                            ." x ".number_format($prob_jawaban_d_melankolis, dec())
                            ." = ".number_format($nilai_melankolis, 20);
        }
        //===============================
        $nilai_plegmatis = $p_plegmatis * $prob_jenis_kelamin_plegmatis * $prob_sekolah_plegmatis *
					$prob_usia_plegmatis * $prob_jawaban_a_plegmatis * $prob_jawaban_b_plegmatis * 
                                        $prob_jawaban_c_plegmatis * $prob_jawaban_d_plegmatis;
	if($show_perhitungan){
        echo "<br>";
	echo "Nilai Plegmatis = ".number_format($p_plegmatis, dec())
                            ." x ".number_format($prob_jenis_kelamin_plegmatis, dec())
                            ." x ".number_format($prob_sekolah_plegmatis, dec())
                            ." x ".number_format($prob_usia_plegmatis, dec())
                            ." x ".number_format($prob_jawaban_a_plegmatis, dec())
                            ." x ".number_format($prob_jawaban_b_plegmatis, dec())
                            ." x ".number_format($prob_jawaban_c_plegmatis, dec())
                            ." x ".number_format($prob_jawaban_d_plegmatis, dec())
                            ." = ".number_format($nilai_plegmatis, 20);

    echo "<br><br>";
        }
    $hasil_prediksi = '';
    if($nilai_sanguin>=$nilai_koleris && $nilai_sanguin>=$nilai_melankolis && $nilai_sanguin>=$nilai_plegmatis){
        $hasil_prediksi = 'Sanguin';
    }
    elseif($nilai_koleris>=$nilai_sanguin && $nilai_koleris>=$nilai_melankolis && $nilai_koleris>=$nilai_plegmatis){
    	$hasil_prediksi = 'Koleris';
    }
    elseif($nilai_melankolis>=$nilai_sanguin && $nilai_melankolis>=$nilai_koleris && $nilai_melankolis>=$nilai_plegmatis){
    	$hasil_prediksi = 'Melankolis';
    }
    elseif($nilai_plegmatis>=$nilai_sanguin && $nilai_plegmatis>=$nilai_koleris && $nilai_plegmatis>=$nilai_melankolis){
    	$hasil_prediksi = 'Plegmatis';
    }

//    $nilai_sanguin = number_format($nilai_sanguin, 50);
//    $nilai_koleris = number_format($nilai_koleris, 50);
    if($id_data_uji>0){
        $res_hasil = update_hasil_prediksi($db_object, $id_data_uji, $hasil_prediksi, 
                $nilai_sanguin, $nilai_koleris, $nilai_melankolis, $nilai_plegmatis);
    }
    return array($hasil_prediksi, $nilai_sanguin, $nilai_koleris, $nilai_melankolis, $nilai_plegmatis);
      
}
	
function update_hasil_prediksi($db_object, $id, $hasil, $sanguin, $koleris, $melankolis, $plegmatis){
	$sql = "UPDATE data_uji "
                . "SET "
                . "kelas_hasil='$hasil', "
                . "nilai_sanguin='$sanguin', "
                . "nilai_koleris='$koleris', "
                . "nilai_melankolis='$melankolis', "
                . "nilai_plegmatis='$plegmatis' 
                WHERE id=$id";
	return $db_object->db_query($sql);
}


function get_jumlah_sum_atribut($db_object, $atribut, $kelas_asli){
	$sql = "SELECT SUM($atribut) FROM data_latih WHERE kelas_asli='$kelas_asli'";
	$res = $db_object->db_query($sql);
	$row = $db_object->db_fetch_array($res);
	return $row[0];
}

function get_jumlah_atribut($db_object, $atribut, $nilai, $kelas_asli){
	$sql = "SELECT COUNT(*) FROM data_latih WHERE $atribut='$nilai' AND kelas_asli='$kelas_asli'";
	$res = $db_object->db_query($sql);
	$row = $db_object->db_fetch_array($res);
	return $row[0];
}


function get_s2_populasi($db_object, $atribut, $kelas_asli, $x_params, $jml_params){
	$sql = "SELECT $atribut FROM data_latih WHERE kelas_asli='$kelas_asli'";
	$res = $db_object->db_query($sql);
	$sum_power = 0;
	while($row = $db_object->db_fetch_array($res)){
		$power = pow($row[0]-$x_params,2);
		$sum_power += $power;
	}
	$s2 = $sum_power/($jml_params-1);
	return $s2;
}
?>

