package com.nas.cruzer;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.support.v4.app.NotificationCompat;
import android.widget.Toast;

import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.UserInfo;
import com.nas.cruzer.util.Util;

public class UpdateReceiver extends BroadcastReceiver{

	Context con;
	JSONParser jparser=new JSONParser();
	public static final String getRidesUrl = GlobalVariable.API+"user-rides-list.php";
	ArrayList<HashMap<String, String>> reqRides;
	SharedPreferences sh;
	int rideAcceptNo=0;

	@Override
	public void onReceive(Context context, Intent intent) {
		con=context;
		sh=con.getSharedPreferences("CRUZER_PREF", Context.MODE_PRIVATE);
		rideAcceptNo=sh.getInt("rideaccno", 0);
		//Toast.makeText(con, "On receiver", Toast.LENGTH_SHORT).show();
		new GetRequestedRides().execute();
	}
	class GetRequestedRides extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Getting data. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			//pDialog.show();
		}

		@Override
		protected String doInBackground(String... st) {

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("user_id", UserInfo.getId()));

			try {
				JSONObject jobj=jparser.makeHttpRequest(getRidesUrl, "POST", params);
				success=jobj.getInt("success");
				s=jobj.getString("message");
				if(success==1){
					reqRides=new ArrayList<HashMap<String,String>>();
					JSONArray jAr=jobj.getJSONArray("ridelist");
					for (int i = 0; i < jAr.length(); i++) {
						JSONObject job=jAr.getJSONObject(i);
						HashMap<String , String> map=new HashMap<String, String>();
						map.put("id", job.getString("id"));
						map.put("driver_name", job.getString("driver_name"));
						map.put("sender_id", job.getString("sender_id"));
						map.put("name", job.getString("name"));
						map.put("phone", job.getString("phone"));
						map.put("droplocation", job.getString("droplocation"));
						map.put("location", job.getString("location"));
						map.put("latitude", job.getString("latitude"));
						map.put("longitude", job.getString("longitude"));
						map.put("accept", job.getString("accept"));
						reqRides.add(map);
					} 
				}

			}catch(Exception e){
				error=1;
			}

			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			//pDialog.dismiss();
			rideAcceptNo=sh.getInt("rideaccno", 0);
			
			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con, "Server is down, Please try again later", Toast.LENGTH_SHORT).show();
				}else
					Util.showNoInternetDialog(con);
				return;
			}

			if(success==1){
				int count=0;
				for (int i = 0; i < reqRides.size(); i++) {
					if(reqRides.get(i).get("accept").equals("1")) count++;
				}
				if(count>rideAcceptNo){
					showNotification();
				}
				SharedPreferences.Editor edit=sh.edit();
				edit.putInt("rideaccno", count);
				edit.commit();
			}else{
				Toast.makeText(con, s, Toast.LENGTH_SHORT).show();
			}
		}

		private void showNotification() {
			NotificationCompat.Builder noti1=new NotificationCompat.Builder(con);
			Intent i1=new Intent(con,UserRequestActivity.class);
			noti1.setContentTitle("Driver accepted your request!");
			noti1.setContentText("Click to see the taxi information ");
			noti1.setSmallIcon(android.R.drawable.ic_dialog_alert);
			noti1.setTicker("New Notification from Cruzer");			

			Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
			noti1.setSound(notification);	

			PendingIntent pi1=PendingIntent.getActivity(con, 100, i1, PendingIntent.FLAG_UPDATE_CURRENT);
			noti1.setAutoCancel(true);
			noti1.setContentIntent(pi1);

			NotificationManager mgr=(NotificationManager) con.getSystemService(Context.NOTIFICATION_SERVICE);
			mgr.notify(110, noti1.build());
		}
	}

}
