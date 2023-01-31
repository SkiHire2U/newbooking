<div style="background:#EEE;width:100%;padding:70px 0">
	<div style="max-width:600px;width:100%;margin:0 auto;">
		<div>
			<p style="margin-top:0;text-align:center">
				<img width="300" src="http://skihire2u.com/wp-content/uploads/2016/09/SkiHire2U-logo-dark-red-new.png" alt="Skihire2u">
			</p>
		</div>
		<table border="0" cellpadding="0" cellspacing="0" width="600">
			<tbody>
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#242223;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;">
							<tbody>
								<tr>
									<td style="padding:36px 48px;display:block">
										<h1 style="color:#ffffff;font-family:Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left">Booking Completed</h1>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600">
							<tbody>
								<tr>
									<td valign="top" id="m_2471269498227967455body_content" style="background-color:#fdfdfd">
										<table border="0" cellpadding="20" cellspacing="0" width="100%">
											<tbody>
												<tr>
													<td valign="top" style="padding:30px 30px 15px;font-family:Arial,sans-serif;">
														<p>Hello {{ $name }},</p>
														<p>You have successfully made a booking with us. This email includes a reference number which you can use to update your booking. Feel free to share this with your other party members so they may add or update their packages.</p>
														<p>Please see the details below:</p>
													</td>
												</tr>
												<tr>
													<td valign="top" style="padding:10px 30px">
														<table cellspacing="0" cellpadding="6" style="width:100%;color:#333;border:1px solid #e4e4e4" border="1">
															<tbody>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		Accommodation:
																	</th>
																	<td style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		{{ $accommodation }}
																	</td>
																</tr>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		Arrival:
																	</th>
																	<td style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		{{ $arrival }}
																	</td>
																</tr>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		First Day on Mountain:
																	</th>
																	<td style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		{{ $mountain }}
																	</td>
																</tr>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		Departure:
																	</th>
																	<td style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		{{ $departure }}
																	</td>
																</tr>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		Reference Code:
																	</th>
																	<td style="text-align:left;vertical-align:middle;padding:12px;font-family:Arial,sans-serif;">
																		{{ $reference }}
																	</td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td valign="top" style="padding:10px 30px">
														<table cellspacing="0" cellpadding="6" style="width:100%;color:#333;border:1px solid #e4e4e4" border="1">
															<thead>
																<tr>
																	<th style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		Name
																	</th>
																	<th style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		Details
																	</th>
																	<th style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		Package and Addons
																	</th>
																	<th style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		Price
																	</th>
																</tr>
															</thead>
															<tbody>
																@foreach($packages as $package)
																<tr>
																	<td style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		<strong>{{ $package['package_renter'] }}</strong>
																		<p style="margin:0;">{{ $select['age'][$package['renter_age']] }}</p>
																		<p style="margin:0;">{{ $package['renter_sex'] }}</p>
																		@if($package['renter_notes'])
																		<p style="margin:0;">Notes: <strong>{{ $package['renter_notes'] }}</strong></p>
																		@endif
																	</td>
																	<td style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		<p style="margin:0;">Ability:<br><strong>{{ $select['level'][$package['renter_ability']] }}</strong></p>
												                        <p style="margin:0;">Height:<br><strong>{{ $select['height'][$package['renter_height']] }}</strong></p>
												                        <p style="margin:0;">Weight:<br><strong>{{ $select['weight'][$package['renter_weight']] }}</strong></p>
												                        <p style="margin:0;">Foot Size:<br><strong>{{ $select['foot'][$package['renter_foot']] }}</strong></p>
																	</td>
																	<td style="text-align:left;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		<p style="margin:0;"><strong>{{ $package['package_name'] }}</strong></p>
																		@if($package['package_level'])
																		<p style="margin:0;"><strong>{{ $package['package_level'] }}</strong></p>
																		@endif
																		<p style="margin:0;">Duration: <strong>{{ $package['rent_days'] }} {{ ($package['rent_days'] == 1) ? 'day' : 'days' }}</strong></p>
																		<p style="margin:0;">Addons:</p>
																		@if($package['addon']['boots'] == 'on')
																		<p style="margin:0;"><strong>Boots</strong></p>
																		@endif
																		@if($package['addon']['helmet'] == 'on')
																		<p style="margin:0;">
																			<strong>Helmet</strong>
												                        	@if( (int) $package['renter_age'] <= 3 || $package['package_type'] == 'Child')
																	    	<small class="free-helmet">(FREE!)</small>
																	    	@endif
																		</p>
																		@endif
																		@if($package['addon']['insurance'] == 'on')
																		<p style="margin:0;"><strong>Insurance</strong></p>
																		@endif
																		@if($package['addon']['boots'] == 'off' && $package['addon']['helmet'] == 'off' && $package['addon']['insurance'] == 'off')
																		<p style="margin:0;"><strong>N/A</strong></p>
																		@endif
																	</td>
																	<td style="text-align:right;vertical-align:middle;padding:5px;font-family:Arial,sans-serif;font-size:12px;">
																		@if ($invoice['rental_prices'][$package['renter_id']]['discount'] != 0)
																		<p style="margin:0;">&euro; {{ $invoice['rental_prices'][$package['renter_id']]['total'] }}</p>
																		<p style="margin:0;text-decoration:line-through;"><small>&euro; {{ $invoice['rental_prices'][$package['renter_id']]['price'] }}</small></p>
																		<p style="margin:0;"><small>(&euro; - {{ $invoice['rental_prices'][$package['renter_id']]['discount'] }}</small>)</p>
																		@else
																		<p style="margin:0;">&euro; {{ $invoice['rental_prices'][$package['renter_id']]['total'] }}</p>
																		@endif
																	</td>
																</tr>
																@endforeach
																<tr>
																	<td colspan="2" style="text-align:right;padding-right:10px;font-family:Arial,sans-serif;font-size:16px;"><strong>Total</strong></td>
																	<td colspan="2" style="text-align:right;padding-right:10px;font-family:Arial,sans-serif;font-size:16px;">
												                        @if ($invoice['discount'] != 0)
												                        <p style="margin:0;"><strong>&euro; {{ $invoice['total'] }}</strong></p>
												                        <p style="margin:0;text-decoration:line-through;"><small>&euro; {{ $invoice['subtotal'] }}</small></p>
												                        <p style="margin:0;">Less: ( &euro; - <small>{{ $invoice['discount'] }}</small> )</p>
												                        @else
												                        <p style="margin:0;"><strong>&euro; {{ $invoice['total'] }}</strong></p>
												                        @endif
												                    </td>
																</tr>
															</tbody>
														</table>
													</td>
												</tr>
												<tr>
													<td valign="top" style="padding:10px 30px;font-family:Arial,sans-serif;">
														<p>If you wish to revisit your packages, go to this link and input your email and reference code: <a href="http://newbooking.skihire2u.com/" target="_blank">http://newbooking.skihire2u.com/</a></p>
														<p>We will contact you shortly before your arrival to arrange a convenient time to come and fit all your equipment so you can now sit back, relax and enjoy your holiday.</p>
													</td>
												</tr>
												<tr>
													<td valign="top" style="padding:10px 30px 30px;font-family:Arial,sans-serif;">
														<p>See you soon,</p>
														<h2 style="margin:0;">SkiHire2U</h2>
														<small>Sit back, relax and we'll come to you</small>
													</td>
												</tr>
											</tbody>
										</table>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#242223;border-radius:3px 3px 0 0!important;color:#ffffff;border-bottom:0;vertical-align:middle;">
							<tbody>
								<tr>
									<td style="padding:36px 48px;display:block;text-align:center;">
										<small style="color:#ffffff;font-family:Arial,sans-serif;">SkiHire2U.com &copy; 2016</small>
									</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>