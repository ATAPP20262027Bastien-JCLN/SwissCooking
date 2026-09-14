param(
    [Parameter(Mandatory = $true, Position = 0)]
    [string]$ProjectName,

    [switch]$Flush
)

$Domain = "${ProjectName}.cfpt.loc"
$IP = "127.0.0.1"
$Server = "::1"
$HostsFile = "$env:SystemRoot\System32\drivers\etc\hosts"
$Comment = "# Added by add-win-vhost.ps1 for $ProjectName"

if ($Flush) {
    Write-Host "🗑 Removing $Domain from hosts file"

    $content = Get-Content -Path $HostsFile |
        Where-Object {
            $_ -notmatch "^\s*#\s*Added by add-win-vhost\.ps1 for $([regex]::Escape($ProjectName))\s*$" -and
            $_ -notmatch "^\s*(127\.0\.0\.1|::1)\s+$([regex]::Escape($Domain))\s*$"
        }

    Set-Content -Path $HostsFile -Value $content

    Write-Host "✅ Removed $Domain from hosts file"
    exit
}

$Exists = Select-String `
    -Path $HostsFile `
    -Pattern "^\s*(127\.0\.0\.1|::1)\s+$([regex]::Escape($Domain))\s*$" `
    -Quiet

if ($Exists) {
    Write-Host "✅ $Domain already exists in hosts file"
}
else {
    Write-Host "➕ Adding $Domain to hosts file"

    Add-Content -Path $HostsFile -Value ""
    Add-Content -Path $HostsFile -Value $Comment
    Add-Content -Path $HostsFile -Value "$IP $Domain"
    Add-Content -Path $HostsFile -Value "$Server $Domain"

    Write-Host "✅ Added successfully"
}