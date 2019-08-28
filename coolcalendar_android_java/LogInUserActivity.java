package fi.jamk.coolcalendar;

import org.json.JSONObject;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.view.KeyEvent;
import android.view.View;
import android.view.inputmethod.EditorInfo;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class LogInUserActivity extends Activity {

	private EditText fUsername;
	private EditText fPassword;
	public static Button fSubmit;
	
	public static Context context;
	public static Activity activity;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_loginuser);

		context = this;
		activity = this;
		initFields();
		initCallbacks();
	}


    private void initFields() {
    	fUsername = (EditText) findViewById(R.id.fUsername);
    	fPassword = (EditText) findViewById(R.id.fPassword);
    	fSubmit = (Button) findViewById(R.id.fSubmit);
    }
    
    private void initCallbacks() {
        // set on last edit text
    	fPassword.setOnEditorActionListener(new TextView.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView view, int actionId, KeyEvent event) {
                if (actionId == EditorInfo.IME_ACTION_DONE) {
                    submit();
                    return true;
                }
                return false;
            }

        });

        fSubmit.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                submit();
            }
        });

    }

    private void submit() {
    	FormUtils.hideKeyboard(context, fPassword);
    	DBService.logInUser(fUsername.getText().toString(),	fPassword.getText().toString());

		Toast.makeText(this, "Please wait...", Toast.LENGTH_LONG).show();
    }
    
    public static void httpCallback(String notify) {
    	if (notify.length() > 8 && notify.substring(0, 7).equals("success")) {
    		// if logging in was successful let's store user's id
    		/*String json_encoded = notify.substring(8);
    		JSONObject json = new JSONObject(json_encoded);
    		json.getJSONArray("user");
    		json.getJSONArray("courses");
    		((MyStorage)MyStorage.getAppContext()).setUserId(Id);*/
    		Toast.makeText(context, "Logged In.", Toast.LENGTH_LONG).show();
    		activity.finish();
    	} else {
    		// something wrong
    		Toast.makeText(context, notify, Toast.LENGTH_LONG).show();
    	}
    	
    }

}
