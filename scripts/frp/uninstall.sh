#!/bin/bash
export PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:$PATH

: '
Copyright (C) 2022 - now  HaoZi Technology Co., Ltd.

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as published
by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>.
'

HR="+----------------------------------------------------"
frpPath="/www/server/frp"

systemctl stop frps
systemctl stop frpc
systemctl disable frps
systemctl disable frpc

rm -rf ${frpPath}
rm -f /etc/systemd/system/frps.service
rm -f /etc/systemd/system/frpc.service
systemctl daemon-reload

panel deletePlugin frp
echo -e $HR
echo "frp 卸载完成"
echo -e $HR
