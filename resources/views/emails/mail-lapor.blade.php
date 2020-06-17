@extends('volunteer::emails.mail-template')

@section('content')
<!-- Start of main-banner -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="banner">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
                           <tbody>
                              <tr>
                                 <!-- start of image -->
                                 <td align="center" st-image="banner-image">
                                    <div class="imgpop">
                                       <a target="_blank" href="#"><img width="156" border="0" height="164" alt="" border="0" style="display:block; border:none; outline:none; text-decoration:none;" src="img/siap.png" class="banner"></a>
                                    </div>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                        <!-- end of image -->
                     </td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of main-banner --> 
<!-- Start of seperator -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="seperator">
   <tbody>
      <tr>
         <td>
            <table width="600" align="center" cellspacing="0" cellpadding="0" border="0" class="devicewidth">
               <tbody>
                  <tr>
                     <td align="center" height="20" style="font-size:1px; line-height:1px;">&nbsp;</td>
                  </tr>
               </tbody>
            </table>
         </td>
      </tr>
   </tbody>
</table>
<!-- End of seperator -->   
<!-- Start Full Text -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" id="backgroundTable" st-sortable="full-text">
   <tbody>
      <tr>
         <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
               <tbody>
                  <tr>
                     <td width="100%">
                        <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="devicewidth">
                           <tbody>
                              <!-- Spacing -->
                              <tr>
                                 <td height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                              </tr>
                              <!-- Spacing -->
                              <tr>
                                 <td>
                                    <table width="560" align="center" cellpadding="0" cellspacing="0" border="0" class="devicewidthinner">
                                       <tbody>
                                          <!-- Title -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; font-size: 22px; color: #ED1C24; text-align:center; line-height: 30px;" st-title="fulltext-heading">
                                                Hi 
                                                @if(isset($report->volunteer->name))
                                                   {{ $report->volunteer->name }}
                                                @elseif(isset($report->admin->name))
                                                   {{ $report->admin->name }}
                                                @else
                                                   Sahabat PMI
                                                @endif


                                             </td>
                                          </tr>
                                          <!-- End of Title -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #3E3E3E; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                Terima kasih sudah melapor.<br>Rincian laporan kamu adalah sebagai berikut:
                                             </td>
                                          </tr>
                                          <!-- End of content -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- invoice -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; text-align:left; line-height: 30px;" st-content="fulltext-content">
                                                <label style="font-size: 12px; font-weight: bold; color: #3E3E3E;">Judul:</label>
                                                <p style="font-size: 12px; color: #3E3E3E; line-height: 20px;">{{ (isset($report->title))? $report->title : ''  }}</p>
                                             </td>
                                          </tr>
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; text-align:left; line-height: 30px;" st-content="fulltext-content">
                                                <label style="font-size: 12px; font-weight: bold; color: #3E3E3E;">Kecamatan:</label>
                                                <p style="font-size: 12px; color: #3E3E3E; line-height: 20px;">
                                                   {{ (isset($report->village->subdistrict))? $report->village->subdistrict->name : ''  }}
                                                </p>
                                             </td>
                                          </tr>
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; text-align:left; line-height: 30px;" st-content="fulltext-content">
                                                <label style="font-size: 12px; font-weight: bold; color: #3E3E3E;">Kelurahan:</label>
                                                <p style="font-size: 12px; color: #3E3E3E; line-height: 20px;">
                                                   {{ (isset($report->village->name))? $report->village->name : ''  }}
                                                </p>
                                             </td>
                                          </tr>
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; text-align:left; line-height: 30px;" st-content="fulltext-content">
                                                <label style="font-size: 12px; font-weight: bold; color: #3E3E3E;">Kabupaten/Kota:</label>
                                                <p style="font-size: 12px; color: #3E3E3E; line-height: 20px;">
                                                   {{ (isset($report->village->subdistrict->city))? $report->village->subdistrict->city->name : ''  }}
                                                </p>
                                             </td>
                                          </tr>
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; text-align:left; line-height: 30px;" st-content="fulltext-content">
                                                <label style="font-size: 12px; font-weight: bold; color: #3E3E3E;">Detail Kejadian:</label>
                                                <p style="font-size: 12px; color: #3E3E3E; line-height: 20px;">
                                                   {{ (isset($report->description))? $report->description : ''  }}
                                                </p>
                                             </td>
                                          </tr>
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #3E3E3E; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                Kami akan meng-kurasi laporan kamu terlebih dahulu, Mohon menunggu sejenak.  
                                             </td>
                                          </tr>
                                          <!-- End of content -->
                                          <!-- spacing -->
                                          <tr>
                                             <td width="100%" height="20" style="font-size:1px; line-height:1px; mso-line-height-rule: exactly;">&nbsp;</td>
                                          </tr>
                                          <!-- End of spacing -->
                                          <!-- content -->
                                          <tr>
                                             <td style="font-family: 'Open Sans', sans-serif; font-size: 14px; color: #3E3E3E; text-align:center; line-height: 30px;" st-content="fulltext-content">
                                                Terima kasih.
                                             </td>
                                          </tr>
                                          <!-- End of content -->
                                       </tbody>
                                    </table>
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
   </tbody>
</table>
<!-- end of full text -->
@endsection