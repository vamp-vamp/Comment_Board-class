<?php
namespace App;

class ImageUploader
{
    private $imageFileName ='';

    public function upload()
    {
        try {
            if (is_uploaded_file($_FILES ['image_file'] ['tmp_name'])) {
                //エラーチェック
                $this->validateUpload();
                //サイズチェック
                $this->validateFilesize();
                //typeチェック・ファイル名作成
                $this->imageFileName = $this->validateImageType();
                //ファイルを一時フォルダから指定したディレクトリに移動
                $this->move($this->imageFileName);
                //セッションへ受け渡し
                $_SESSION['success'] = "アップロード完了";
            //redirect
            } else {
                throw new Exception("ファイルが選択されていません。");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    private function validateUpload()
    {
        //ファイルのMIMEタイプをチェック
        if (!isset($_FILES['image_file']['error']) || !is_int($_FILES['image_file']['error'])) {
            throw new Exception("パラメータが不正です");
        }
        // $_FILES['image_file']['error'] の値を確認
        switch ($_FILES['image_file']['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // ファイル未選択
                throw new Exception("ファイルが選択されていません");
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過 (フォームで設定した場合のみ)
                throw new Exception("画像のファイルサイズは2MBまでです");
            default:
                throw new Exception("エラーが発生しました");
        }
    }

    private function validateFilesize()
    {
        if ($_FILES['image_file']['size'] > 2097152) {
            throw new Exception("画像のファイルサイズは2MBまでです");
        }
    }

    private function validateImageType()
    {
        $image = uniqid();
        switch (exif_imagetype($_FILES['image_file']['tmp_name'])) {
            case IMAGETYPE_JPEG:
                return $image .'.jpg';
            case IMAGETYPE_GIF:
                return $image .'.gif';
            case IMAGETYPE_PNG:
                return $image .'.png';
            default:
                throw new Exception("アップロード可能なファイルは[jpeg] [png] [gif]のみです。");
        }
    }

    private function move($image_file_name)
    {
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], 'upload/'. $image_file_name)) {
            return true;
        } else {
            throw new Exception("ファイル保存時にエラーが発生しました");
        }
    }

    public function getImageFileName()
    {
        return $this->imageFileName;
    }
}
