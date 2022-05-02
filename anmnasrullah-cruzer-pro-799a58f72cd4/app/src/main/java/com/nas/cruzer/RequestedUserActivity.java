package com.nas.cruzer;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import com.google.android.gms.maps.CameraUpdate;
import com.google.android.gms.maps.CameraUpdateFactory;
import com.google.android.gms.maps.GoogleMap;
import com.google.android.gms.maps.MapFragment;
import com.google.android.gms.maps.model.BitmapDescriptorFactory;
import com.google.android.gms.maps.model.LatLng;
import com.google.android.gms.maps.model.MarkerOptions;
import com.google.android.gms.maps.model.PolylineOptions;
import com.nas.cruzer.R;
import com.nas.cruzer.util.DirectionsJSONParser;
import com.nas.cruzer.util.GPSTracker;
import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.UserInfo;
import com.nas.cruzer.util.Util;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.HorizontalScrollView;
import android.widget.TextView;
import android.widget.Toast;

public class RequestedUserActivity extends Activity implements OnClickListener{
	
	public static final String dataSendUrl = GlobalVariable.API+"update-request.php";
	TextView nameTv,numberTv,pickupTv,dropLocTv;
	Button cancelBtn,accBtn,completeBtn;
	Context con;
	JSONParser jparser=new JSONParser();
	ProgressDialog dialogz;
	LatLng picklatlng;
	LatLng droplatlng;
	public static HashMap<String, String> request;
	GPSTracker gps;
	private GoogleMap googleMap;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.requesteduser_details_layout);
		con=RequestedUserActivity.this;
		gps=new GPSTracker(this);
		init();
		String pickloc = request.get("location");
		String droploc = request.get("droplocation");
		try {
			nameTv.setText("Name : " + request.get("name"));
			numberTv.setText("Phone no. : " + request.get("phone"));
			pickupTv.setText("Pickup from : " + pickloc.substring(0, pickloc.indexOf("@")));
			dropLocTv.setText("Drop : " + droploc.substring(0, droploc.indexOf("@")));

			try {
				// Loading map
				initilizeMap();

			} catch (Exception e) {
				e.printStackTrace();
			}
			getActionBar().setHomeButtonEnabled(true);
			getActionBar().setDisplayHomeAsUpEnabled(true);
			String[] p_latLng = pickloc.substring(pickloc.indexOf("@") + 1, pickloc.length()).split(",");
			String[] d_latLng = droploc.substring(droploc.indexOf("@") + 1, droploc.length()).split(",");
			double p_latitude = Double.parseDouble(p_latLng[0]);
			double p_longitude = Double.parseDouble(p_latLng[1]);
			double d_latitude = Double.parseDouble(d_latLng[0]);
			double d_longitude = Double.parseDouble(d_latLng[1]);
			picklatlng = new LatLng(p_latitude, p_longitude);
			droplatlng = new LatLng(d_latitude, d_longitude);
			getDirectionsUrl(picklatlng, droplatlng);
		}catch (Exception e){
			e.printStackTrace();
		}
	}
	private void initilizeMap() {
		if (googleMap == null) {
			googleMap = ((MapFragment) getFragmentManager().findFragmentById(
					R.id.map)).getMap();

			googleMap.setMyLocationEnabled(true);

			LatLng latLng = new LatLng(gps.getLatitude(), gps.getLongitude());

			//googleMap.setOnMarkerClickListener(this);
			// check if map is created successfully or not
			if (googleMap == null) {
				Toast.makeText(getApplicationContext(),
						"Sorry! unable to create maps", Toast.LENGTH_SHORT)
						.show();
			}
		}
	}
	private void init() {
		nameTv=(TextView) findViewById(R.id.requestDetailsName);
		numberTv=(TextView) findViewById(R.id.requestDetailsPhone);
		pickupTv=(TextView) findViewById(R.id.requestDetailsPickup);
		dropLocTv=(TextView) findViewById(R.id.requestDetailsDrop);
		
		cancelBtn=(Button) findViewById(R.id.requestDetailsCancelBtn);
		accBtn=(Button) findViewById(R.id.requestDetailsAccBtn);
		completeBtn=(Button) findViewById(R.id.requestDetailsCompleteBtn);
		
		accBtn.setOnClickListener(this);
		cancelBtn.setOnClickListener(this);
		completeBtn.setOnClickListener(this);
		
		if(request.get("accept").equals("1")){
			accBtn.setVisibility(View.GONE);
			cancelBtn.setVisibility(View.GONE);
			completeBtn.setVisibility(View.VISIBLE);
		}else
		if(request.get("accept").equals("2")){
			accBtn.setVisibility(View.GONE);
			cancelBtn.setVisibility(View.GONE);
			completeBtn.setVisibility(View.GONE);
		}else
		if(request.get("accept").equals("3")){
			accBtn.setVisibility(View.GONE);
			cancelBtn.setVisibility(View.GONE);
			completeBtn.setVisibility(View.GONE);
		}else
		if(request.get("accept").equals("0")){
			accBtn.setVisibility(View.VISIBLE);
			cancelBtn.setVisibility(View.VISIBLE);
			completeBtn.setVisibility(View.GONE);
		}
		
	}
	
	@Override
	public boolean onOptionsItemSelected(MenuItem item) { 
	        switch (item.getItemId()) {
	        case android.R.id.home: 
	           finish();
	            return true;
	        }

	    return super.onOptionsItemSelected(item);
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.requestDetailsCancelBtn:
			new SendData().execute("3");
			break;
		case R.id.requestDetailsAccBtn:
			new SendData().execute("1");
			break;	
		case R.id.requestDetailsCompleteBtn:
			new SendData().execute("2");
			break;	
		}
	}
	
	class SendData extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;


		boolean driver=false;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Sending data. Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			pDialog.show();
		}

		@Override
		protected String doInBackground(String... st) {

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("id", request.get("id")));
			params.add(new BasicNameValuePair("accept", st[0]));

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

			if(error==1){
				if(Util.isConnectingToInternet(con)){
					Toast.makeText(con, "Server is down, Please try again later", Toast.LENGTH_SHORT).show();
				}else
					Util.showNoInternetDialog(con);
				return;
			}

			Toast.makeText(con, s, Toast.LENGTH_LONG).show();
			if(success==0){

			}else if(success==1){
				setResult(RESULT_OK);
				finish();
			}
		}
	}
	private void getDirectionsUrl(LatLng picklatlng, LatLng droplatlng){
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
	private String downloadUrl(String strUrl) throws IOException {
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
			try {
				dialogz = new ProgressDialog(RequestedUserActivity.this);
				dialogz.setMessage("Finding the most preferable road");
				dialogz.show();
				dialogz.setCancelable(false);
				dialogz.setCanceledOnTouchOutside(false);
			}catch (Exception e){
				e.printStackTrace();
			}
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
			/*fare = (0.50 * Double.parseDouble(str)) + 4;
			rate.setText("$ " + fare);*/
			MarkerOptions mark=new MarkerOptions();
			mark.position(droplatlng);
			Bitmap icon = BitmapFactory.decodeResource(getResources(),R.drawable.des);
			Bitmap nicon = scaleDown(icon,100,false);
			mark.icon(BitmapDescriptorFactory.fromBitmap(nicon));
			MarkerOptions mark_p=new MarkerOptions();
			mark_p.position(picklatlng);
			Bitmap icon_p = BitmapFactory.decodeResource(getResources(),R.drawable.user);
			Bitmap nicon_p = scaleDown(icon_p,100,false);
			mark_p.icon(BitmapDescriptorFactory.fromBitmap(nicon_p));
			googleMap.addMarker(mark_p);
			googleMap.addMarker(mark);
			if(googleMap!=null) {
				googleMap.addPolyline(lineOptions);

				CameraUpdate cameraUpdate = CameraUpdateFactory.newLatLngZoom(picklatlng, 10);
				googleMap.animateCamera(cameraUpdate);

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
}
