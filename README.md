
注意事項：

1. 先將程式與資料庫備份一份(PS：如果有資料庫)。

2. 設定 /tpl_c/user 及 /tpl_c/admin 及 /data 資料夾可寫入。

3. 刪除 .git 資料夾

4. 建立資料庫

5. 將 /sql 資料夾下的 *.sql 資料匯入資料庫後刪除。勿放在網站上。

6. 新增資料庫帳號密碼

7. 在 / 下設定 env.php 檔案，內容為 <?php $environment="release"; ?>
	此檔案格式為 UTF-8，無 BOM 檔案。

8. 設定 php.ini，將以下參數改成 1G，才能上傳大型檔案。
	memory_limit = 1G
	post_max_size = 1G
	upload_max_filesize = 1G

9. 設定 php.ini，file_uploads = On

10. 設定 php.ini，設定修改以下參數(設定可發信)：
	SMTP = localhost

11. 後台帳密

12. 將此檔刪除，請勿放在網站上。