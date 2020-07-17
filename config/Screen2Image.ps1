[reflection.assembly]::LoadWithPartialName( "System.Drawing" ) > $null;
$Bitmap = New-Object System.Drawing.Bitmap {{width}}, {{height}};
$Size = New-Object System.Drawing.Size {{width}}, {{height}};
$FromImage = [System.Drawing.Graphics]::FromImage( $Bitmap );
$FromImage.CopyFromScreen( {{x}}, {{y}}, 0, 0, $size );
$FromImage.Dispose();
$Bitmap.Save( "{{file}}", [System.Drawing.Imaging.ImageFormat]::png );
$Bitmap.Dispose();