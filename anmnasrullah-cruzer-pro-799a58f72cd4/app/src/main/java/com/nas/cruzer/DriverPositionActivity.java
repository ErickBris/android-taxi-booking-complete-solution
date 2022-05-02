 package com.nas.cruzer;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;
import java.net.URLEncoder;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.HashMap;
import java.util.List;
import java.util.Locale;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import android.Manifest;
import android.app.Activity;
import android.app.AlarmManager;
import android.app.Dialog;
import android.app.PendingIntent;
import android.app.ProgressDialog;
import android.content.ActivityNotFoundException;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.location.Address;
import android.location.Geocoder;
import android.location.Location;
import android.location.LocationManager;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.speech.RecognizerIntent;
import android.support.v4.app.ActivityCompat;
import android.text.TextUtils;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.Window;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.RadioButton;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.maps.CameraUpdate;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.GoogleMap.OnMarkerClickListener;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.Marker;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.PolylineOptions;
import com.nas.cruzer.util.DirectionsJSONParser;
import com.nas.cruzer.util.GPSTracker;
import com.nas.cruzer.util.GeocodingLocation;
import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.UserInfo;
import com.nas.cruzer.util.Util;

public class DriverPositionActivity extends Activity implements OnMarkerClickListener {
	double fare;
	MarkerOptions options,options1;
	ProgressDialog dialogz;
	Boolean isgps = true;
	TextView info,d_name;
	JSONArray placeids ;
	public static final String getDataUrl = GlobalVariable.API+"getlocations.php";
	public static final String taxiRequUrl =GlobalVariable.API+"requesttaxi.php";
	private static final String PLACES_API_BASE = "https://maps.googleapis.com/maps/api/place";
	private static final String TYPE_AUTOCOMPLETE = "/autocomplete";
	private static final String OUT_JSON = "/json";
	private static final String API_KEY = "AIzaSyBSAKawEbW9GcZmaidtfKW-lRF9avAAvXM";
	String [] placeid;
	JSONArray predsJsonArray;
	// Google Map
	private GoogleMap googleMap;
	Context con;
	ArrayList<Driver> drivers;
	JSONParser jparser=new JSONParser();
	GPSTracker gps;

	HashMap<Marker, Driver> markers;
	ArrayList<Marker> mapMarker;

	Handler handler;
	Runnable run;

	Driver selectedDriver;
	TextView spchBtn;
	private final int REQ_CODE_SPEECH_INPUT = 100;

	LatLng picklatlng = null;
	LatLng droplatlng = null;

	LinearLayout ratebox;
	Intent alIntent;
	AlarmManager alarmManager;
	PendingIntent appIntent;
	TextView rate;
	SharedPreferences sh;
	AutoCompleteTextView dropLoc,pickme;
	@Override
	protected void onCreate(Bundle savedInstanceState) {

		super.onCreate(savedInstanceState);
		setContentView(R.layout.driver_position_layout);
		sh=getSharedPreferences("CRUZER_PREF", MODE_PRIVATE);
		drivers=new ArrayList<Driver>();
		gps=new GPSTracker(this);
		con=DriverPositionActivity.this;
		ratebox=(LinearLayout) findViewById(R.id.ratebox);
		spchBtn=(TextView) findViewById(R.id.speechBtn);
		dropLoc=(AutoCompleteTextView) findViewById(R.id.driverDropLoc);
		rate=(TextView) findViewById(R.id.rate);
		pickme=(AutoCompleteTextView) findViewById(R.id.mypickloc);
		pickme.setAdapter(new PostedVoteAdapter(getApplicationContext(), R.layout.tag_places_list));
		pickme.setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
				if(picklatlng!=null){
					googleMap.clear();
				}
				String locationtitle = (String) parent.getItemAtPosition(position);
				try{
				getAddLatLon ss = new getAddLatLon();
				picklatlng= ss.execute(predsJsonArray.getJSONObject(position).getString("place_id")).get();
					MarkerOptions mark=new MarkerOptions();
					mark.position(picklatlng);
					Bitmap icon = BitmapFactory.decodeResource(getResources(),R.drawable.user);
					Bitmap nicon = scaleDown(icon,100,false);
					mark.icon(BitmapDescriptorFactory.fromBitmap(nicon));
					googleMap.addMarker(mark);
					CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(picklatlng, 14);
					googleMap.animateCamera(cameraUpdate);
					getDirectionsUrl(picklatlng,droplatlng);
				}catch (Exception e){
					e.printStackTrace();
				}
				pickme.setText(locationtitle);
				InputMethodManager inputMethodManager = (InputMethodManager) getSystemService(Activity.INPUT_METHOD_SERVICE);
				inputMethodManager.hideSoftInputFromWindow(view.getWindowToken(), 0);
			}
		});
		dropLoc.setAdapter(new PostedVoteAdapter(getApplicationContext(), R.layout.tag_places_list));
		dropLoc.setOnItemClickListener(new AdapterView.OnItemClickListener() {
			@Override
			public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
				String locationtitle = (String) parent.getItemAtPosition(position);
				if(droplatlng!=null){
					googleMap.clear();
				}
				try{
					getAddLatLon ss = new getAddLatLon();
					droplatlng= ss.execute(predsJsonArray.getJSONObject(position).getString("place_id")).get();
					MarkerOptions mark=new MarkerOptions();
					mark.position(droplatlng);
					Bitmap icon = BitmapFactory.decodeResource(getResources(),R.drawable.des);
					Bitmap nicon = scaleDown(icon,100,false);
					mark.icon(BitmapDescriptorFactory.fromBitmap(nicon));
					googleMap.addMarker(mark);
					CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(droplatlng, 14);
					googleMap.animateCamera(cameraUpdate);


					getDirectionsUrl(picklatlng,droplatlng);

				}catch (Exception e){
					e.printStackTrace();
				}
				dropLoc.setText(locationtitle);
				InputMethodManager inputMethodManager = (InputMethodManager) getSystemService(Activity.INPUT_METHOD_SERVICE);
				inputMethodManager.hideSoftInputFromWindow(view.getWindowToken(), 0);
			}
		});
		if(!Util.isGPSOn(this)){
			GPSTracker.showSettingsAlert(this);
		}

		try {
			// Loading map
			initilizeMap();

		} catch (Exception e) {
			e.printStackTrace();
		}
		if (ActivityCompat.checkSelfPermission(DriverPositionActivity.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(DriverPositionActivity.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
			ActivityCompat.requestPermissions(DriverPositionActivity.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 1);
			return;
		}else{
			LatLng latLng = new LatLng(gps.getLatitude(), gps.getLongitude());
			CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(latLng, 14);
			googleMap.animateCamera(cameraUpdate);
		}

		markers=new HashMap<Marker, Driver>();
		mapMarker=new ArrayList<Marker>();

		spchBtn.setOnClickListener(new OnClickListener() {
			@Override
			public void onClick(View arg0) {
				/*Intent intent = new Intent(RecognizerIntent.ACTION_RECOGNIZE_SPEECH);
				intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE_MODEL,
						RecognizerIntent.LANGUAGE_MODEL_FREE_FORM);
				intent.putExtra(RecognizerIntent.EXTRA_LANGUAGE, Locale.getDefault());
				intent.putExtra(RecognizerIntent.EXTRA_PROMPT,
						getString(R.string.speech_prompt));
				try {
					startActivityForResult(intent, REQ_CODE_SPEECH_INPUT);
				} catch (ActivityNotFoundException a) {
					Toast.makeText(getApplicationContext(),
							getString(R.string.speech_not_supported),
							Toast.LENGTH_SHORT).show();
				}*/
				findTheNearestDriver(null);
			}
		});

		new GetLocations().execute();

		startUpdateCheck();
	}

	@Override
	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
		super.onActivityResult(requestCode, resultCode, data);
		switch (requestCode) {
			case REQ_CODE_SPEECH_INPUT:
				if (resultCode == RESULT_OK && null != data) {
					ArrayList<String> result = data
							.getStringArrayListExtra(RecognizerIntent.EXTRA_RESULTS);
					String talk=result.get(0);
					Toast.makeText(con, talk, Toast.LENGTH_LONG).show();

					if((talk.contains("driver near to my location") )||
							(talk.contains("driver near to me"))||
							(talk.contains("today pick me up"))||
							(talk.contains("nearest today cab"))||
							(talk.contains("today"))||
							(talk.contains("nearest driver"))||
							(talk.contains("who is near to me"))||
							(talk.contains("nearest driver to my location"))||
							(talk.contains("nearest driver to me"))||
							(talk.contains("request nearest driver"))||
							(talk.contains("who is the nearest driver"))||
							(talk.contains("closest driver to me"))||
							(talk.contains("find the closest driver"))){
						findTheNearestDriver(null);
					}else if((talk.contains("the driver near"))||
							(talk.contains("driver near"))||
							(talk.contains("taxi near to"))||
							(talk.contains("driver near to"))||
							(talk.contains("taxi near"))||
							(talk.contains("driver at"))||
							(talk.contains("tada me"))||
							(talk.contains("cabs near"))||
							(talk.contains("cab near"))||
							(talk.contains("cabs at"))||
							(talk.contains("cab near to"))||
							(talk.contains("tada near to")))

					{
						Toast.makeText(con, talk.substring(15), Toast.LENGTH_LONG).show();
						findTheNearestDriver(talk.substring(15).trim());
					}
				}
				break;
		}
	}

	public void findTheNearestDriver(String loc){
		Location my=new Location("My");
		if(loc==null){
			/*my.setLatitude(gps.getLatitude());
			my.setLongitude(gps.getLongitude());*/
			my.setLatitude(picklatlng.latitude);
			my.setLongitude(picklatlng.longitude);
		}else{
			my=new Location(loc);
		}
		ArrayList<Float> distance=new ArrayList<Float>();
		for (int i = 0; i < mapMarker.size(); i++) {
			LatLng pos=mapMarker.get(i).getPosition();
			Location markLoc=new Location("Driver");
			markLoc.setLatitude(pos.latitude);
			markLoc.setLongitude(pos.longitude);
			String aa  = String.valueOf(my.distanceTo(markLoc));
			distance.add(my.distanceTo(markLoc));

			Log.e("DISTANCE",aa+" - "+drivers.get(i).getInfo());

		}
		int min=0;
		float minDis=distance.get(min);
		for (int i = 0; i < distance.size(); i++) {
			if(distance.get(i)<minDis){
				minDis=distance.get(i);
				min=i;
			}
		}
		Marker m=mapMarker.get(min);
		onMarkerClick(m);
	}

	public void startUpdateCheck(){
		alIntent= new Intent(this, UpdateReceiver.class);
		appIntent = PendingIntent.getBroadcast(this,1000, alIntent,PendingIntent.FLAG_UPDATE_CURRENT);
		alarmManager = (AlarmManager) getSystemService(ALARM_SERVICE);

		// 1 min in millisecond= 1*60*1000;
		alarmManager.setRepeating(AlarmManager.RTC,System.currentTimeMillis(),30*1000, appIntent);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		getMenuInflater().inflate(R.menu.usermenu, menu);
		return true;
	}

	@Override
	public boolean onOptionsItemSelected(MenuItem item) {
		switch (item.getItemId()) {
			case R.id.userProfileMenu:
				startActivity(new Intent(this, UserEditProfileActivity.class));
				break;
			case R.id.userDetailsMenu:
				startActivity(new Intent(this, UserRequestActivity.class));
				break;
			case R.id.useroutmneu:
				SharedPreferences.Editor edit=sh.edit();
				edit.putString("loginemail", null);
				edit.putString("loginpass", null);
				edit.putBoolean("type", false);
				edit.commit();


				startActivity(new Intent(this, LoginActivity.class));
				finish();
				break;
		}
		return false;
	}

	/**
	 * function to load map. If map is not created it will create it for you
	 * */
	private void initilizeMap() {
		if (googleMap == null) {
			googleMap = ((MapFragment) getFragmentManager().findFragmentById(
					R.id.map)).getMap();

			googleMap.setMyLocationEnabled(true);

			LatLng latLng = new LatLng(gps.getLatitude(), gps.getLongitude());
			CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(latLng, 14);
			googleMap.animateCamera(cameraUpdate);

			//googleMap.setOnMarkerClickListener(this);
			// check if map is created successfully or not
			if (googleMap == null) {
				Toast.makeText(getApplicationContext(),
						"Sorry! unable to create maps", Toast.LENGTH_SHORT)
						.show();
			}
		}
	}



	@Override
	public boolean onMarkerClick(Marker marker) {
		try {
			ratebox.setVisibility(View.GONE);
			Driver dv = markers.get(marker);
			/*info.setText(dv.getInfo());
			d_name.setText(dv.getName());*/

			selectedDriver = dv;
			showDriverDetailsWindow(marker, dv.getId(), dv.getInfo(), dv.getCost(), dv.getNumber(),dv.getName());
		}catch (Exception e){
			e.printStackTrace();
		}
		return true;
	}

	public void showDriverDetailsWindow(Marker mark, String id, final String info, String cost, String phone,String name){
		isgps = true;
		final Dialog dialog=new Dialog(this,android.R.style.Theme_DeviceDefault_Light_Dialog_NoActionBar_MinWidth);
		dialog.requestWindowFeature(Window.FEATURE_NO_TITLE);

		dialog.setContentView(R.layout.driver_details_layout);
		dialog.setCancelable(true);

		TextView drivername=(TextView) dialog.findViewById(R.id.drivername);
		TextView vehicleInfo=(TextView) dialog.findViewById(R.id.driverVehicleInfo);
		TextView phn=(TextView) dialog.findViewById(R.id.driverPhone);
		TextView loc=(TextView) dialog.findViewById(R.id.driverCurrentLocation);
		TextView droplocname=(TextView) dialog.findViewById(R.id.droplocname);

		TextView cancelBtn=(TextView) dialog.findViewById(R.id.close);
		cancelBtn.setOnClickListener(new OnClickListener() {

			@Override
			public void onClick(View v) {
				dialog.cancel();
			}
		});


		drivername.setText(name);
		vehicleInfo.setText(info);
		phn.setText(phone);
		loc.setText(pickme.getText().toString());
		droplocname.setText(dropLoc.getText().toString());
		RequestTaxi requestTaxi = new RequestTaxi();
		requestTaxi.execute(pickme.getText().toString()+"@"+picklatlng.latitude+","+picklatlng.longitude,dropLoc.getText().toString()+"@"+droplatlng.latitude+","+droplatlng.longitude);
		dialog.show();
	}

	class GetLocations extends AsyncTask<String, String, String>{

//		ProgressDialog pDialog;
		String toastText="Internet Problem";
		String regiresult="";
		int success=0;
		int error=0;
		String errmsg="Server is down";

		@Override
		protected void onPreExecute() {
			try {
				super.onPreExecute();
				/*pDialog = new ProgressDialog(con);
				pDialog.setMessage("Updating drivers locations. Please wait...");
				pDialog.setIndeterminate(false);
				pDialog.setCancelable(true);
				pDialog.show();*/
			}catch (Exception r){
				r.printStackTrace();
			}
		}

		@Override
		protected String doInBackground(String... para) {

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			JSONObject json=jparser.makeHttpRequest(getDataUrl, "POST", params);

			try {
				success=json.getInt("success");
				if(success==1){

					drivers=new ArrayList<Driver>();

					JSONArray sounds=json.getJSONArray("location");
					for (int i = 0; i < sounds.length(); i++) {
						JSONObject jobj=sounds.getJSONObject(i);
						Driver d=new Driver();
						d.setId(jobj.getString("id"));
						d.setName(jobj.getString("name"));
						d.setEmail(jobj.getString("email"));
						d.setNumber(jobj.getString("number"));
						d.setLatitude(jobj.getString("latitude"));
						d.setLongitude(jobj.getString("longitude"));
						d.setInfo(jobj.getString("info"));
						d.setCost(jobj.getString("cost"));
						d.setBearing(jobj.getString("bearing"));
						drivers.add(d);
					}
				}

			} catch (JSONException e) {
				e.printStackTrace();
				error=1;
			}catch (Exception e) {
				error=1;
			}
			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			//Toast.makeText(MainActivity.this, s+" "+result, Toast.LENGTH_SHORT).show();
			//pDialog.dismiss();

			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con,"Server is down. Please try again", Toast.LENGTH_SHORT).show();
				}else{
					Util.showNoInternetDialog(con);
				}
				return;
			}

			if(success==0){
				Toast.makeText(con,"Data loading failed", Toast.LENGTH_SHORT).show();
			}else if (success==1){
				markers=new HashMap<Marker, Driver>();
				removeMarkers();

				for (int i = 0; i < drivers.size(); i++) {
					MarkerOptions mark=new MarkerOptions();
					mark.position(new LatLng(Double.parseDouble(drivers.get(i).getLatitude()),Double.parseDouble(drivers.get(i).getLongitude())));
					//mark.title(drivers.get(i).getName());
					Bitmap icon = BitmapFactory.decodeResource(getResources(),R.drawable.taxi);
					Bitmap nicon = scaleDown(icon,100,false);
					mark.icon(BitmapDescriptorFactory.fromBitmap(nicon));
					//mark.snippet(drivers.get(i).getInfo()+","+drivers.get(i).getCost()+" Rs.pKm, Ph. "+drivers.get(i).getNumber());
					mark.anchor(0.5f,0.5f);
					mark.rotation(Float.parseFloat(drivers.get(i).getBearing()));
					Marker m=googleMap.addMarker(mark);
					mapMarker.add(m);
					markers.put(m, drivers.get(i));
				}
				scheduleThread();
			}
		}
	}
	public static Bitmap scaleDown(Bitmap realImage, float maxImageSize,
								   boolean filter) {
		float ratio = Math.min(
				(float) maxImageSize / realImage.getWidth(),
				(float) maxImageSize / realImage.getHeight());
		int width = Math.round((float) ratio * realImage.getWidth());
		int height = Math.round((float) ratio * realImage.getHeight());

		Bitmap newBitmap = Bitmap.createScaledBitmap(realImage, width,
				height, filter);
		return newBitmap;
	}
	private void removeMarkers() {
		for (Marker m : mapMarker) {
			m.remove();
		}
		mapMarker.clear();
	}

	public void scheduleThread(){
		handler=new Handler();
		run=new Runnable() {

			@Override
			public void run() {
				// This method will be executed once the timer is over
				if(Util.isConnectingToInternet(con)){
					new GetLocations().execute();

				}else{
					Toast.makeText(con, "Internet is not active", Toast.LENGTH_SHORT).show();
				}
			}
		};
		handler.postDelayed(run, 10000);
	}

	class RequestTaxi extends AsyncTask<String, String, String>{

		ProgressDialog pDialog;
		String toastText="Internet Problem";
		String regiresult="";
		int success=0;
		int error=0;
		String msg="";

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Requesting for the nearest Taxi. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			pDialog.show();
		}

		@Override
		protected String doInBackground(String... para) {

			Calendar cal=Calendar.getInstance();
			String timedate=cal.get(Calendar.DAY_OF_MONTH)+"-"+(cal.get(Calendar.MONTH)+1)+"-"+cal.get(Calendar.YEAR)+" "+
					cal.get(Calendar.HOUR_OF_DAY)+":"+cal.get(Calendar.MINUTE)+":"+cal.get(Calendar.SECOND);

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("driver_id", selectedDriver.getId()));
			params.add(new BasicNameValuePair("driver_email", selectedDriver.getEmail()));
			params.add(new BasicNameValuePair("driver_name", selectedDriver.getName()));
			params.add(new BasicNameValuePair("sender_id", UserInfo.getId()));
			params.add(new BasicNameValuePair("name", UserInfo.getName()));
			params.add(new BasicNameValuePair("phone", UserInfo.getPhonenumber()));
			params.add(new BasicNameValuePair("location", para[0]));
			params.add(new BasicNameValuePair("droplocation", para[1]));
			params.add(new BasicNameValuePair("latitude", gps.getLatitude()+""));
			params.add(new BasicNameValuePair("longitude", gps.getLongitude()+""));
			params.add(new BasicNameValuePair("timedate",timedate));
			params.add(new BasicNameValuePair("rate",String.valueOf(fare) ));

			JSONObject json=jparser.makeHttpRequest(taxiRequUrl, "POST", params);

			try {
				success=json.getInt("success");
				msg=json.getString("message");

			}catch (Exception e) {
				error=1;
				msg=e.getMessage();
			}
			return null;
		}

		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			//Toast.makeText(MainActivity.this, s+" "+result, Toast.LENGTH_SHORT).show();
			pDialog.dismiss();
			Toast.makeText(con, msg, Toast.LENGTH_SHORT).show();

			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con,"Something went wrong", Toast.LENGTH_SHORT).show();
				}else{
					Util.showNoInternetDialog(con);
				}
				return;
			}
		}
	}
	class getAddLatLon extends AsyncTask<String, String, LatLng>{
		ProgressDialog pDialog;
		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(false);
			pDialog.show();
		}

		@Override
		protected LatLng doInBackground(String... para) {

			JSONObject json=jparser.makeHttpRequest("https://maps.googleapis.com/maps/api/place/details/json?placeid="+para[0]+"&key="+API_KEY, "GET",null);
			LatLng latlng = null;
			try {
				JSONObject jso = new JSONObject(json.toString());
			latlng= new LatLng(jso.getJSONObject("result").getJSONObject("geometry").getJSONObject("location").getDouble("lat"),
					jso.getJSONObject("result").getJSONObject("geometry").getJSONObject("location").getDouble("lng"));
			} catch (JSONException e) {
				e.printStackTrace();
			}
			return latlng;
		}

		@Override
		protected void onPostExecute(LatLng result) {
			super.onPostExecute(result);
			pDialog.dismiss();
		}
	}

	@Override
	protected void onPause() {
		super.onPause();
		if(handler!=null){
			handler.removeCallbacks(run);
			run=null;
			handler=null;
		}
	}
	@Override
	protected void onResume() {
		super.onResume();
		initilizeMap();
		if(handler==null){
			scheduleThread();
		}
		if (ActivityCompat.checkSelfPermission(DriverPositionActivity.this, Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED && ActivityCompat.checkSelfPermission(DriverPositionActivity.this, Manifest.permission.ACCESS_COARSE_LOCATION) != PackageManager.PERMISSION_GRANTED) {
			ActivityCompat.requestPermissions(DriverPositionActivity.this, new String[]{Manifest.permission.ACCESS_FINE_LOCATION}, 1);
			return;
		}else{
			LatLng latLng = new LatLng(gps.getLatitude(), gps.getLongitude());
			CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(latLng, 14);
			googleMap.animateCamera(cameraUpdate);
		}
	}

	@Override
	protected void onDestroy() {
		super.onDestroy();
		alIntent= new Intent(this, UpdateReceiver.class);
		appIntent = PendingIntent.getBroadcast(this,1000, alIntent,PendingIntent.FLAG_UPDATE_CURRENT);
		alarmManager = (AlarmManager) getSystemService(ALARM_SERVICE);
		alarmManager.cancel(appIntent);
	}
	public ArrayList<String> autocomplete(String input) {

		ArrayList<String> resultList = null;

		HttpURLConnection conn = null;
		StringBuilder jsonResults = new StringBuilder();
		try {
			StringBuilder sb = new StringBuilder(PLACES_API_BASE + TYPE_AUTOCOMPLETE + OUT_JSON);
			sb.append("?key=" + API_KEY);
			//7sb.append("&components=country:QA");
			sb.append("&input=" + URLEncoder.encode(input, "utf8"));

			URL url = new URL(sb.toString());

			System.out.println("URL: "+url);
			conn = (HttpURLConnection) url.openConnection();
			InputStreamReader in = new InputStreamReader(conn.getInputStream());

			// Load the results into a StringBuilder
			int read;
			char[] buff = new char[1024];
			while ((read = in.read(buff)) != -1) {
				jsonResults.append(buff, 0, read);
			}
		} catch (MalformedURLException e) {
			return resultList;
		} catch (IOException e) {
			return resultList;
		} finally {
			if (conn != null) {
				conn.disconnect();
			}
		}

		try {

			// Create a JSON object hierarchy from the results
			JSONObject jsonObj = new JSONObject(jsonResults.toString());
			predsJsonArray = jsonObj.getJSONArray("predictions");
			Log.e("jsonObj", String.valueOf(jsonObj));

			// Extract the Place descriptions from the results
			resultList = new ArrayList<String>(predsJsonArray.length());
			placeids = new JSONArray();
			for (int i = 0; i < predsJsonArray.length(); i++) {

				System.out.println(predsJsonArray.getJSONObject(i).getString("place_id"));
				System.out.println(predsJsonArray.getJSONObject(i).getString("description"));
				System.out.println(predsJsonArray.getJSONObject(i).getString("terms"));
				System.out.println("============================================================");
				//resultList.add(predsJsonArray.getJSONObject(i).getString("description")+ "                                                       #" +predsJsonArray.getJSONObject(i).getString("place_id"));
				resultList.add(predsJsonArray.getJSONObject(i).getString("description"));

			}
		} catch (JSONException e) {
		}

		return resultList;
	}


	class PostedVoteAdapter extends ArrayAdapter<String> implements Filterable {
		private ArrayList<String> resultList;

		public PostedVoteAdapter(Context context, int textViewResourceId) {
			super(context, textViewResourceId);
		}

		@Override
		public int getCount() {
			return resultList.size();
		}

		@Override
		public String getItem(int index) {
			return resultList.get(index);
		}

		@Override
		public Filter getFilter() {
			Filter filter = new Filter() {
				@Override
				protected FilterResults performFiltering(CharSequence constraint) {
					FilterResults filterResults = new FilterResults();
					if (constraint != null) {
						// Retrieve the autocomplete results.
						resultList = autocomplete(constraint.toString());

						// Assign the data to the FilterResults
						filterResults.values = resultList;
						filterResults.count = resultList.size();
					}
					return filterResults;
				}

				@Override
				protected void publishResults(CharSequence constraint, FilterResults results) {
					if (results != null && results.count > 0) {
						Log.e("results", String.valueOf(results));
						notifyDataSetChanged();
					} else {
						notifyDataSetInvalidated();
					}
				}
			};
			return filter;
		}
	}

	private void getDirectionsUrl(LatLng picklatlng,LatLng droplatlng){
		if(picklatlng!=null && droplatlng!=null) {
			String str_origin = "origin=" + picklatlng.latitude + "," + picklatlng.longitude;
			String str_dest = "destination=" + droplatlng.latitude + "," + droplatlng.longitude;
			String sensor = "sensor=false";
			String parameters = str_origin + "&" + str_dest + "&" + sensor;
			String output = "json";
			String url = "https://maps.googleapis.com/maps/api/directions/" + output + "?" + parameters;
			DownloadTask downloadTask = new DownloadTask();
			downloadTask.execute(url);
		}
	}
	private class DownloadTask extends AsyncTask<String, Void, String>{
		@Override
		protected String doInBackground(String... url) {
			String data = "";
			try{
				data = downloadUrl(url[0]);
			}catch(Exception e){
				Log.d("Background Task",e.toString());
			}
			return data;
		}
		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			ParserTask parserTask = new ParserTask();
			parserTask.execute(result);
		}
	}
	private String downloadUrl(String strUrl) throws IOException{
		InputMethodManager inputMethodManager = (InputMethodManager) getSystemService(Activity.INPUT_METHOD_SERVICE);
		inputMethodManager.hideSoftInputFromWindow(ratebox.getWindowToken(), 0);
		String data = "";
		InputStream iStream = null;
		HttpURLConnection urlConnection = null;
		try{
			URL url = new URL(strUrl);
			urlConnection = (HttpURLConnection) url.openConnection();
			urlConnection.connect();
			iStream = urlConnection.getInputStream();

			BufferedReader br = new BufferedReader(new InputStreamReader(iStream));

			StringBuffer sb  = new StringBuffer();
			String line = "";
			while( ( line = br.readLine())  != null){
				sb.append(line);
			}
			data = sb.toString();
			br.close();
		}catch(Exception e){
			e.printStackTrace();
		}finally{
			iStream.close();
			urlConnection.disconnect();
		}
		return data;
	}
	private class ParserTask extends AsyncTask<String, Integer, List<List<HashMap<String,String>>> >{
		// Parsing the data in non-ui thread
		@Override
		protected List<List<HashMap<String, String>>> doInBackground(String... jsonData) {

			JSONObject jObject;
			List<List<HashMap<String, String>>> routes = null;

			try{
				jObject = new JSONObject(jsonData[0]);
				DirectionsJSONParser parser = new DirectionsJSONParser();


				// Starts parsing data
				routes = parser.parse(jObject);
			}catch(Exception e){
				e.printStackTrace();
			}
			return routes;
		}

		@Override
		protected void onPreExecute() {
			super.onPreExecute();

			dialogz = new ProgressDialog(DriverPositionActivity.this);
			dialogz.setMessage("Finding the most preferable road");
			dialogz.show();
			dialogz.setCancelable(false);
			dialogz.setCanceledOnTouchOutside(false);
		}

		@Override
		protected void onPostExecute(List<List<HashMap<String, String>>> result) {
			ArrayList<LatLng> points = null;
			PolylineOptions lineOptions = null;
			MarkerOptions markerOptions = new MarkerOptions();
			String distance = "";
			String duration = "";
			dialogz.dismiss();
			if(result.size()<1){
				Toast.makeText(getBaseContext(), "Road name cannot be identified, Please choose a nearest location", Toast.LENGTH_SHORT).show();
				return;
			}

			for(int i=0;i<result.size();i++){
				points = new ArrayList<LatLng>();
				lineOptions = new PolylineOptions();
				List<HashMap<String, String>> path = result.get(i);
				for(int j=0;j<path.size();j++){
					HashMap<String,String> point = path.get(j);
					if(j==0){
						distance = (String)point.get("distance");
						continue;
					}else if(j==1){
						duration = (String)point.get("duration");
						continue;
					}
					double lat = Double.parseDouble(point.get("lat"));
					double lng = Double.parseDouble(point.get("lng"));
					LatLng position = new LatLng(lat, lng);

					points.add(position);
				}

				// Adding all the points in the  route to LineOptions
				lineOptions.addAll(points);
				lineOptions.width(18);
				lineOptions.color(Color.parseColor("#2E2E2E"));

			}

			String str = distance;
			Log.e("DISTACE",str);

			if (str != null && str.length() > 0) {
				str = str.substring(0, str.length()-2);
			}
			str = str.replaceAll(",", "");
			fare = (0.50 * Double.parseDouble(str)) + 4;
			rate.setText("$ " + fare);
			ratebox.setVisibility(View.VISIBLE);
			if(googleMap!=null) {
				googleMap.addPolyline(lineOptions);

				CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(picklatlng, 12);
				googleMap.animateCamera(cameraUpdate);

			}
		}
	}
}
