package fi.jamk.coolcalendar;

import java.util.Calendar;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.util.DisplayMetrics;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.GridView;
import android.widget.TextView;

public class UserInterfaceActivity extends Activity {
	
	private TextView tUserMsg;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_userinterface);

		initFields();
	}


    private void initFields() {
    	tUserMsg = (TextView) findViewById(R.id.tUserMsg);
    }
    
    public void onClickLogoutButton(View view) {
    	((MyStorage)MyStorage.getAppContext()).setUserId("");
    	finish();
    }

    public void onClickSettingsButton(View view) {
		//Intent intent = new Intent(UserInterfaceActivity.this, SettingsActivity.class);
		//startActivity(intent);
    }
    
    public void onClickCreateCourseButton(View view) {
		Intent intent = new Intent(UserInterfaceActivity.this, CreateCourseActivity.class);
		startActivity(intent);
    }

}
