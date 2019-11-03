<?php //test
// $comment_image_dir = 'upload/';
// $product_comment_image = '88888.gif';
//       $imageresize = new ImageResize();
//       $width_max = 400; //リサイズ後の画像幅の最大値
//       $imageresize->resize($comment_image_dir .$product_comment_image, $width_max);
?>

<?php
class ImageResize {
  //初期化
  private $new_width = 0;
  private $new_height = 0;

  public function resize($image_file_name, $width_max) {
    //元の画像名を指定してサイズを取得
    list($width_orig, $height_orig, $type) = getimagesize($image_file_name);
echo "ファイル・タイプは" .$type ."<br>";
echo "横幅は" .$width_orig ."<br>";
echo "縦幅は" .$height_orig ."<br>";
    //元画像が画像幅の最大値をオーバーしていた場合
    if ($width_max < $width_orig) {
echo "最大値を超えています<br>";
      //横幅縮小比率を計算
      $this->new_height = $height_orig * ($width_max / $width_orig);
      $this->new_width = $width_max;
echo "新しい横幅は" .$this->new_width ."<br>";
echo "新しい縦幅は" .$this->new_height ."<br>";
      //元の画像から新しい画像を作る準備
      switch ($type) {
        case IMAGETYPE_JPEG:
          $base_image = imagecreatefromjpeg($image_file_name);
          break;
        case IMAGETYPE_PNG:
          $base_image = imagecreatefrompng($image_file_name);
          break;
        case IMAGETYPE_GIF:
          $base_image = imagecreatefromgif($image_file_name);
          break;
        default:
          throw new RuntimeException('対応していないファイル形式です。: ', $type);
      }

var_dump($base_image);
echo "<br>";
echo $base_image ."<br>";
echo "-----<br>";
      //サイズを指定して新しい画像のキャンバスを作成
      $image = imagecreatetruecolor($this->new_width, $this->new_height);
var_dump($image);

  //gifやpngなら
  switch ($type) {
    //以下のようにまとめてしまうことも可
    // case IMAGETYPE_JPEG:
    //   $base_image = imagecreatefromjpeg($image_file_name);
    //   $image = imagecreatetruecolor($this->new_width, $this->new_height);
    //   echo "jpegjpegjpeg";  
    //   break;
    case IMAGETYPE_PNG:
      //アルファブレンディングを無効
      imagealphablending($image, false);
      //アルファフラグを設定
      imagesavealpha($image, true);
      break;
    case IMAGETYPE_GIF:
      $alpha = imagecolortransparent($image_file_name);  // 元画像から透過色を取得する
      imagefill($image, 0, 0, $alpha);       // その色でキャンバスを塗りつぶす
      imagecolortransparent($image, $alpha); // 塗りつぶした色を透過色として指定する
      break;
    // default:
    //   throw new RuntimeException('対応していないファイル形式です。: ', $type);
  }
      //画像のコピーと伸縮
      imagecopyresampled($image, $base_image, 0, 0, 0, 0, $this->new_width, $this->new_height, $width_orig, $height_orig);

      //コピーした画像を出力する
      echo $image_file_name;
      switch ($type) {
        case IMAGETYPE_JPEG:
          imageJPEG($image, $image_file_name, 100);
          break;
        case IMAGETYPE_PNG:
          imagePNG($image, $image_file_name, 9);
          break;
        case IMAGETYPE_GIF:
          imageGIF($image, $image_file_name);
          break;
      }

      // メモリを開放する
      imagedestroy($image);
      imagedestroy($base_image);

    } else {
echo "最大値以内です<br>";
echo "そのまま維持します<br>";
//       $this->new_width = $width_orig;
//       $this->new_height = $height_orig;
// echo "新しい横幅は" .$this->new_width ."<br>";
// echo "新しい縦幅は" .$this->new_height ."<br>";
    }


  }
  
}

?>

