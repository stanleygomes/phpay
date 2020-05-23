<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>@yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>

	</head>

	<body bgcolor="#f5f5f5" style="background-color:#f5f5f5; font-family:Roboto,sans-serif">

		<table width="640" align="center" cellpadding="0" cellspacing="0"><tbody>
			<tr>
				<td height="20"></td>
			</tr>

			<tr>
				<td width="10" bgcolor="#f5f5f5" rowspan="2"></td>
				<td height="0" bgcolor="#d0d0d0" align="center" style="border-radius: 3px 3px 0 0;">
					<table width="100%">
						<tbody>
							<tr>
								<td align="left" style="padding:10px 5px; text-align:center; font-size:36px; color:#777">
		                            <img src="{{ $message->embed(public_path() . '/img/logo.png') }}" style="max-width:150px;max-height:100px"/>
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td width="10" bgcolor="#f5f5f5" rowspan="2"></td>
			</tr>

			<tr bgcolor="#ffffff">
				<td align="center">
					<table width="100%" style="font-size:14px; line-height:20px; color:#333; margin-top:15px">
						<tbody>
							<tr>
								<td align="left" style="line-height:1; padding: 5px 24px 24px 24px">
									@yield('body')
								</td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>

			<tr>
				<td></td>
				<td>
					<table width="100%" style="font-size:14px; color:#888; line-height:17px; margin-top:15px">
						<tbody>
							<tr>
								<td style="text-align:center;">
								</td>
							</tr>
						</tbody>
					</table>
				</td>
				<td></td>
			</tr>
			</tbody>
		</table>
	</body>
</html>
