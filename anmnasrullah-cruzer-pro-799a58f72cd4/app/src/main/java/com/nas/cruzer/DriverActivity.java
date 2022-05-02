package com.nas.cruzer;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONObject;

import android.app.Activity;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.location.Location;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.support.v4.app.NotificationCompat;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.AdapterView.OnItemSelectedListener;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.CompoundButton;
import android.widget.CompoundButton.OnCheckedChangeListener;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.Switch;
import android.widget.TextView;
import android.widget.Toast;
import android.widget.ToggleButton;

import com.nas.cruzer.util.GPSTracker;
import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.UserInfo;
import com.nas.cruzer.util.Util;

public class DriverActivity extends Activity{

	public static final String getRidesUrl = GlobalVariable.API+"requested-rides-list.php";
	public static final String modeSendUrl = GlobalVariable.API+"receievemode.php";
	public static final String getModeURL = GlobalVariable.API+"getmode.php";

	Context con;
	JSONParser jparser=new JSONParser();
	GPSTracker gps;
	Location oldloc = null;
	final String dataSendUrl="http://mboajobapp.com/taxi/receievelocation.php";
	Handler handler;
	Runnable run;
	ListView rideList;
	Switch modeBtn;

	ArrayList<HashMap<String, String>> rides;
	
	
	SharedPreferences sh;
	int notAccReq=0;
	String bearing;
	Spinner categoryFilter;
	String dropdownItems[]={"All","New Requests","Accepted Rides","Completed Rides","Cancelled Rides"};

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		gps=new GPSTracker(this);
		con=DriverActivity.this;
		setContentView(R.layout.driver_activity_layout);
		

		rideList=(ListView) findViewById(R.id.driverRequestedRides);
		modeBtn=(Switch) findViewById(R.id.modeToggleBtn);
		categoryFilter=(Spinner) findViewById(R.id.driverRequestedRidesListFilter);
		categoryFilter.setAdapter(new ArrayAdapter<String>(this	, android.R.layout.simple_dropdown_item_1line, dropdownItems));



		modeBtn.setOnCheckedChangeListener(new OnCheckedChangeListener() {

			@Override
			public void onCheckedChanged(CompoundButton arg0, boolean check) {
				if(check) {
					new SendModeData().execute("1");
				}else new SendModeData().execute("0");
			}
		});
		getMode getmode = new getMode();
		getmode.execute();
		categoryFilter.setOnItemSelectedListener(new OnItemSelectedListener() {

			@Override
			public void onItemSelected(AdapterView<?> arg0, View arg1,
					int i, long arg3) {
				new GetRequestedRides().execute();
			}

			@Override
			public void onNothingSelected(AdapterView<?> arg0) {

			}
		});

		sh=getSharedPreferences("CRUZER_PREF", MODE_PRIVATE);
		notAccReq=sh.getInt("notaccreq", 0);


		new GetRequestedRides().execute();
		new SendLocation().execute();

		rideList.setOnItemClickListener(new OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> arg0, View arg1, int i,
					long arg3) {
				RequestedUserActivity.request=rides.get(i);
				Intent in=new Intent(DriverActivity.this, RequestedUserActivity.class);
				startActivityForResult(in, 101);
			}
		});

		if(!Util.isGPSOn(this)){
			GPSTracker.showSettingsAlert(this);
		}
	}	

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.driver_menu, menu);
		return super.onCreateOptionsMenu(menu);
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()) {
			case R.id.userProfileMenu:
				startActivity(new Intent(this, UserEditProfileActivity.class));
				break;
		case R.id.driverRefreshMenu:
			new GetRequestedRides().execute();
			break;
		case R.id.driverLogoutMenu:
			SharedPreferences.Editor edit=sh.edit();
			edit.putString("loginemail", null);
			edit.putString("loginpass", null);
			edit.putBoolean("type", false);
			edit.commit();
			
			startActivity(new Intent(this, LoginActivity.class));
			
			finish();
			break;
		}
		return super.onOptionsItemSelected(item);
	}

	class GetRequestedRides extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			try {
				pDialog = new ProgressDialog(con);
				pDialog.setMessage("Getting data. Please wait...");
				pDialog.setIndeterminate(false);
				pDialog.setCancelable(true);
				//pDialog.show();
			}catch (Exception e){
				e.printStackTrace();
			}
		}

		@Override
		protected String doInBackground(String... st) {

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("user_email", UserInfo.getEmail()));

			try {
				JSONObject jobj=jparser.makeHttpRequest(getRidesUrl, "POST", params);
				success=jobj.getInt("success");
				s=jobj.getString("message");
				if(success==1){
					rides=new ArrayList<HashMap<String,String>>();
					JSONArray jAr=jobj.getJSONArray("ridelist");
					for (int i = 0; i < jAr.length(); i++) {
						JSONObject job=jAr.getJSONObject(i);
						HashMap<String , String> map=new HashMap<String, String>();
						map.put("id", job.getString("id"));
						map.put("driver_id", job.getString("driver_id"));
						map.put("sender_id", job.getString("sender_id"));
						map.put("name", job.getString("name"));
						map.put("phone", job.getString("phone"));
						map.put("droplocation", job.getString("droplocation"));
						map.put("location", job.getString("location"));
						map.put("latitude", job.getString("latitude"));
						map.put("longitude", job.getString("longitude"));
						map.put("timedate", job.getString("timedate"));
						map.put("accept", job.getString("accept"));

						if(categoryFilter.getSelectedItemPosition()==0)
							rides.add(map);
						else if(categoryFilter.getSelectedItemPosition()==1){
							if(job.getString("accept").equals("0"))
								rides.add(map);
						}else if(categoryFilter.getSelectedItemPosition()==2){
							if(job.getString("accept").equals("1"))
								rides.add(map);
						}else if(categoryFilter.getSelectedItemPosition()==3){
							if(job.getString("accept").equals("2"))
								rides.add(map);
						}else if(categoryFilter.getSelectedItemPosition()==4){
							if(job.getString("accept").equals("3"))
								rides.add(map);
						}
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
			pDialog.dismiss();


			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con, "Server is down, Please try again later", Toast.LENGTH_SHORT).show();
				}else
					Util.showNoInternetDialog(con);
				return;
			}
			notAccReq=sh.getInt("notaccreq", 0);

			if(success==1){
				ListAdapter adapter=new ListAdapter();
				rideList.setAdapter(adapter);
				if(categoryFilter.getSelectedItemPosition()==0 || categoryFilter.getSelectedItemPosition()==2){
					int count=0;
					for (int i = 0; i < rides.size(); i++) {
						if(rides.get(i).get("accept").equals("0")) count++;
					}
					if(count>notAccReq)showNotification();

					SharedPreferences.Editor edit=sh.edit();
					edit.putInt("notaccreq", count);
					edit.commit();
				}
			}else{
				Toast.makeText(DriverActivity.this, s, Toast.LENGTH_SHORT).show();
			}
		}
	}

	private void showNotification() {
		NotificationCompat.Builder noti1=new NotificationCompat.Builder(con);
		Intent i1=new Intent(con,DriverActivity.class);
		noti1.setContentTitle("New taxi request!");
		noti1.setContentText("Click to see the customer information ");
		noti1.setSmallIcon(android.R.drawable.ic_dialog_alert);
		noti1.setTicker("New notification from Cruzer");			

		Uri notification = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);
		noti1.setSound(notification);	

		PendingIntent pi1=PendingIntent.getActivity(con, 100, i1, PendingIntent.FLAG_UPDATE_CURRENT);
		noti1.setAutoCancel(true);
		noti1.setContentIntent(pi1);

		NotificationManager mgr=(NotificationManager) con.getSystemService(Context.NOTIFICATION_SERVICE);
		mgr.notify(110, noti1.build());
	}

	class ListAdapter extends BaseAdapter{

		@Override
		public int getCount() {
			return rides.size();
		}

		@Override
		public Object getItem(int i) {
			return rides.get(i);
		}

		@Override
		public long getItemId(int i) {
			return i;
		}

		@Override
		public View getView(int i, View view, ViewGroup parent) {
			LayoutInflater inflater = getLayoutInflater();
			View rowView= inflater.inflate(R.layout.request_ride_single_view, parent, false);
			TextView name=(TextView) rowView.findViewById(R.id.driverNameTv);
			TextView from=(TextView) rowView.findViewById(R.id.driverFromTv);
			TextView destination=(TextView) rowView.findViewById(R.id.driverDestinationTv);
			TextView timeDate=(TextView) rowView.findViewById(R.id.drivertimeDate);
			TextView accept=(TextView) rowView.findViewById(R.id.driverAccept);
		

			name.setText("Passenger Name: "+rides.get(i).get("name").trim());
			timeDate.setText("Time : "+rides.get(i).get("timedate"));
			try {
				String pickloc = rides.get(i).get("location");
				String droploc = rides.get(i).get("droplocation");
				from.setText("Pickup from : " + pickloc.substring(0, pickloc.indexOf("@")));
				destination.setText("Drop : " + droploc.substring(0, droploc.indexOf("@")));
			}catch (Exception e){
				e.printStackTrace();
			}
			if(rides.get(i).get("accept").equals("0")){
				accept.setText("Pending");
				accept.setBackgroundResource(R.color.pending);
			}else if(rides.get(i).get("accept").equals("1")){
				accept.setText("Accepted");
				accept.setBackgroundResource(R.color.accepted);
			}else if(rides.get(i).get("accept").equals("2")){
				accept.setText("Completed");
				accept.setBackgroundResource(R.color.completed);
			}else if(rides.get(i).get("accept").equals("3")){
				accept.setText("Cancelled");
				accept.setBackgroundResource(R.color.cancelled);
			}

			return rowView;
		}

	}
	public void scheduleThread(){
		handler=new Handler();
		run=new Runnable() {

			@Override
			public void run() {
				if(Util.isConnectingToInternet(DriverActivity.this)){
					gps=new GPSTracker(con);
					new SendLocation().execute();
					new GetRequestedRides().execute();

					//Toast.makeText(con, gps.getLatitude()+" "+gps.getLongitude(), Toast.LENGTH_SHORT).show();
				}else{
					Toast.makeText(con, "Internet is not active", Toast.LENGTH_SHORT).show();
				}
			}
		};
		handler.postDelayed(run, 10000);
	}

	class SendLocation extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;


		boolean driver=false;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			try{
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Updating locations. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			//pDialog.show();
			}catch (Exception e){
				e.printStackTrace();
			}
		}

		@Override
		protected String doInBackground(String... st) {
			if(oldloc==null){
				bearing = "90";
			}else {
				bearing = String.valueOf(gps.getBearing(oldloc));
			}
			Log.e("BEARING",bearing);
			oldloc=gps.getLocation();
			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("email", UserInfo.getEmail()));
			params.add(new BasicNameValuePair("latitude", gps.getLatitude()+""));
			params.add(new BasicNameValuePair("longitude", gps.getLongitude()+""));
			params.add(new BasicNameValuePair("bearing", bearing));

			try {
				JSONObject jobj=jparser.makeHttpRequest(dataSendUrl, "POST", params);
				success=jobj.getInt("success");
				s=jobj.getString("message");

			}catch(Exception e){
				error=1;
			}

			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			pDialog.dismiss();

			//Toast.makeText(con, "On execute", Toast.LENGTH_LONG).show();

			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con, "Server is down, Please try again later", Toast.LENGTH_SHORT).show();
				}else
					Util.showNoInternetDialog(con);
				return;
			}

			//Toast.makeText(con, s, Toast.LENGTH_LONG).show();
			if(success==0){

			}else if(success==1){
				scheduleThread();
			}
		}
	}
	class SendModeData extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			try{
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Updating status. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			//pDialog.show();
			}catch (Exception e){
				e.printStackTrace();
			}
		}

		@Override
		protected String doInBackground(String... st) {
			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("email", UserInfo.getEmail()));
			params.add(new BasicNameValuePair("mode", st[0]));

			try {
				JSONObject jobj=jparser.makeHttpRequest(modeSendUrl, "POST", params);
				success=jobj.getInt("success");
				s=jobj.getString("message");

			}catch(Exception e){
				error=1;
			}

			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			pDialog.dismiss();

			//Toast.makeText(con, "On execute", Toast.LENGTH_LONG).show();

			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con, "Server is down, Please try again later", Toast.LENGTH_SHORT).show();
				}else
					Util.showNoInternetDialog(con);
				return;
			}

			Toast.makeText(con, s, Toast.LENGTH_LONG).show();
		}
	}
	class getMode extends AsyncTask<String, String, String>{

		String status="";
		@Override
		protected void onPreExecute() {
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(String... st) {
			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("email", UserInfo.getEmail()));
			try {
				JSONObject jobj=jparser.makeHttpRequest(getModeURL, "POST", params);
				status =jobj.getString("status");
			}catch(Exception e){

			}
			return null;
		}
		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			//Toast.makeText(con, "On execute", Toast.LENGTH_LONG).show();

			if(status.matches("1")) {
				modeBtn.setChecked(true);
			}else {
				modeBtn.setChecked(false);
			}

		}
	}

	@Override
	protected void onDestroy() {
		super.onDestroy();
		if(handler!=null){
			handler.removeCallbacks(run);
			run=null;
		}
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		if(requestCode==101 && resultCode==RESULT_OK){
			new GetRequestedRides().execute();
		}
	}
	@Override
	protected void onPause() {
		super.onPause();
		scheduleThread();
		onResume();
	}
	@Override
	protected void onResume() {
		super.onResume();
		scheduleThread();
	}

}
