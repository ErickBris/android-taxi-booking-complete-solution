package com.nas.cruzer;

import java.util.ArrayList;
import java.util.List;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;
import org.json.JSONException;
import org.json.JSONObject;

import android.app.Activity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.SharedPreferences;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.TextUtils;
import android.view.MenuItem;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.nas.cruzer.R;
import com.nas.cruzer.util.GlobalVariable;
import com.nas.cruzer.util.JSONParser;
import com.nas.cruzer.util.UserInfo;
import com.nas.cruzer.util.Util;

public class UserEditProfileActivity extends Activity implements OnClickListener{

	public static final String updateURL = GlobalVariable.API+"user-edit-profile.php";
	public static final String getStatsURL = GlobalVariable.API+"getstats.php";
	EditText nameEt,numberEt,passwordEt;
	Button cancelBtn,saveBtn;
	Context con;
	LinearLayout stats_ln;
	TextView earning,ride;
	JSONParser jparser=new JSONParser();

	SharedPreferences sh;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.user_edit_profile_layout);
		con=UserEditProfileActivity.this;
		init();
		if(UserInfo.getName()!=null)
			nameEt.setText(UserInfo.getName());
		if(UserInfo.getPhonenumber()!=null)
			numberEt.setText(UserInfo.getPhonenumber());
		if(UserInfo.getPassword()!=null)
			passwordEt.setText(UserInfo.getPassword());
		
		getActionBar().setHomeButtonEnabled(true);
		getActionBar().setDisplayHomeAsUpEnabled(true);

		sh=getSharedPreferences("CRUZER_PREF", MODE_PRIVATE);
		boolean driver=sh.getBoolean("type", false);
		if(driver){
			stats_ln.setVisibility(View.VISIBLE);
		}else {
			stats_ln.setVisibility(View.GONE);
		}
		getStats getstats = new getStats();
		getstats.execute();
	}

	private void init() {
		earning=(TextView) findViewById(R.id.earnigs);
		ride=(TextView) findViewById(R.id.rides);
		nameEt=(EditText) findViewById(R.id.userChangeProfileNameEt);
		numberEt=(EditText) findViewById(R.id.userChangeProfileNumberEt);
		passwordEt=(EditText) findViewById(R.id.userChangeProfilePasswordEt);
		stats_ln=(LinearLayout) findViewById(R.id.stats_ln);

		cancelBtn=(Button) findViewById(R.id.userChangeProfileCancelBtn);
		saveBtn=(Button) findViewById(R.id.userChangeProfileSaveBtn);
		cancelBtn.setOnClickListener(this);
		saveBtn.setOnClickListener(this);
	}
	class getStats extends AsyncTask<String, String, String>{

		String earnings="";
		String rides="";
		@Override
		protected void onPreExecute() {
			super.onPreExecute();
		}

		@Override
		protected String doInBackground(String... st) {
			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("email", UserInfo.getEmail()));
			try {
				JSONObject jobj=jparser.makeHttpRequest(getStatsURL, "POST", params);
				earnings =jobj.getString("earnings");
				rides =jobj.getString("rides");
			}catch(Exception e){
			}
			return null;
		}
		@Override
		protected void onPostExecute(String result) {
			super.onPostExecute(result);
			ride.setText(rides);
			earning.setText("$"+earnings);
			//Toast.makeText(con, "On execute", Toast.LENGTH_LONG).show();


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
		case R.id.userChangeProfileCancelBtn:
			finish();
			break;
		case R.id.userChangeProfileSaveBtn:
			if(Util.isConnectingToInternet(this)){
				if(!TextUtils.isEmpty(nameEt.getText().toString())){
					if(!TextUtils.isEmpty(numberEt.getText().toString())){
						if(!TextUtils.isEmpty(passwordEt.getText().toString())){
							new UpdateInfo().execute();
						}else  Util.showToast(this, "Please enter your password");
					}else  Util.showToast(this, "Please enter your phone number");
				}else Util.showToast(this, "Please enter your name");
			}else Util.showNoInternetDialog(this);
			break;
		}
	}

	class UpdateInfo extends AsyncTask<String, String, String>{
		ProgressDialog pDialog;
		String s="";
		int success=-1;
		int error=0;

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			pDialog=new ProgressDialog(con);
			pDialog.setMessage("Data updating, Please wait...");
			pDialog.setIndeterminate(false);
			pDialog.setCancelable(true);
			pDialog.show();
		}


		@Override
		protected String doInBackground(String... st) {
			String email=UserInfo.getEmail();
			String password=passwordEt.getText().toString();
			String name=nameEt.getText().toString();
			String number=numberEt.getText().toString();

			List<NameValuePair> params=new ArrayList<NameValuePair>();
			params.add(new BasicNameValuePair("email", email));
			params.add(new BasicNameValuePair("name", name));
			params.add(new BasicNameValuePair("password", password));
			params.add(new BasicNameValuePair("number", number));


			try {
				JSONObject jobj=jparser.makeHttpRequest(updateURL, "POST", params);
				success=jobj.getInt("success");
				s=jobj.getString("message");

				if(success==1){
					UserInfo.setName(name);
					UserInfo.setPhonenumber(number);
					UserInfo.setPassword(password);
				}

			} catch (JSONException e) {
				//	e.printStackTrace();
				error=1;
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
			
			Toast.makeText(con, s, Toast.LENGTH_SHORT).show();
		}
	}

}
