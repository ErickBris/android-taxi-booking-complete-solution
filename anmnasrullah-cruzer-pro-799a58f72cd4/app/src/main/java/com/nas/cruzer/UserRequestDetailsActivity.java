package com.nas.cruzer;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONObject;

import com.nas.cruzer.R;
import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.Util;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

/**
 * @author Nasrullah
 *
 */
public class UserRequestDetailsActivity extends Activity implements OnClickListener{
	
	TextView nameTv,numberTv,pickupTv,dropTv;
	Button trackBtn;
	public static HashMap<String, String> request;
	Context con;
	JSONParser jparser=new JSONParser();
	public static final String dataSendUrl = GlobalVariable.API+"update-request.php";
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.user_request_details_layout);
		con=UserRequestDetailsActivity.this;
		init();
		nameTv.setText("Name : "+request.get("driver_name").trim());
		numberTv.setText(request.get("phone").trim());
		String pickloc = request.get("location");
		String droploc = request.get("droplocation");
		pickupTv.setText("Pickup from : "+pickloc.substring(0,pickloc.indexOf("@")));
		dropTv.setText("Drop : "+ droploc.substring(0,droploc.indexOf("@")));
		
		getActionBar().setHomeButtonEnabled(true);
		getActionBar().setDisplayHomeAsUpEnabled(true);
	}

	private void init() {
		nameTv=(TextView) findViewById(R.id.userRequestDetailsName);
		numberTv=(TextView) findViewById(R.id.userRequestDetailsNumber);
		pickupTv=(TextView) findViewById(R.id.userRequestDetailsPickupLocation);
		dropTv=(TextView) findViewById(R.id.userRequestDetailsDropLocation);
		trackBtn=(Button) findViewById(R.id.driverTrackBtn);
		trackBtn.setOnClickListener(this);
	}

	@Override
	public void onClick(View v) {
		switch (v.getId()) {
		case R.id.driverTrackBtn:
			if(request.get("accept").equals("1")){
				Intent i=new Intent(this, DriverTrackActivity.class);
				i.putExtra("driver_email", request.get("driver_email"));
				startActivity(i);
			}else{
				Util.showToast(con, "Only accepted requests are alowed to track");
			}
			break;
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

}

