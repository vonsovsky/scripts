package fi.jamk.coolcalendar;

import android.app.Activity;
import android.content.Context;
import android.os.Bundle;
import android.view.View;
import android.view.KeyEvent;
import android.view.inputmethod.EditorInfo;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

public class CreateGroupActivity extends Activity {

	private EditText fFullname;
	private EditText fUsername;
	private EditText fPassword;
	private EditText fPassword2;
	private EditText fEmail;
	public static Button fSubmit;
	
	public static Context context;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_creategroup);

		context = this;
		initFields();
		initCallbacks();
		
		fSubmit.setVisibility(View.VISIBLE);
	}

	public void onClickCloseButton(View view) {
		finish();
	}
	
    private void initFields() {
    	fFullname = (EditText) findViewById(R.id.fFullname);
    	fUsername = (EditText) findViewById(R.id.fUsername);
    	fPassword = (EditText) findViewById(R.id.fPassword);
    	fPassword2 = (EditText) findViewById(R.id.fPassword2);
    	fEmail = (EditText) findViewById(R.id.fEmail);
    	fSubmit = (Button) findViewById(R.id.fSubmit);
    }
    
    private void initCallbacks() {
        // set on last edit text
    	fEmail.setOnEditorActionListener(new TextView.OnEditorActionListener() {
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

    private boolean validateFields() {
    	//getString(R.string.username_is_required)
    	
    	boolean valid = true;
    	
    	if (fFullname.getText().toString().length() == 0) {
    		fFullname.setError("Name is required.");
    		valid = false;
    	}
    	if (fUsername.getText().toString().length() == 0) {
    		fUsername.setError("Username is required.");
    		valid = false;
    	}
    	if (fPassword.getText().toString().length() == 0) {
    		fPassword.setError("Password is required.");
    		valid = false;
    	}
    	if (fPassword2.getText().toString().length() == 0) {
    		fPassword2.setError("Retyping password is required.");
    		valid = false;
    	}
    	if (!fPassword.getText().toString().equals(fPassword2.getText().toString())) {
    		fPassword2.setError("Passwords do not match.");
    		valid = false;
    	}
    	
    	return valid;
    }
    
    private void submit() {
    	if (validateFields()) {
	    	FormUtils.hideKeyboard(context, fEmail);
	    	String retString =
		    	DBService.createUser(fFullname.getText().toString(), fUsername.getText().toString(),
		    			fPassword.getText().toString(), fEmail.getText().toString());
	    	if (retString != "") {
	    		Toast.makeText(this, retString, Toast.LENGTH_LONG).show();
	    	} else {
	    		Toast.makeText(this, "Please wait...", Toast.LENGTH_LONG).show();
	    	}
    	}
    }
    
    public static void httpCallback(String notify) {
    	if (notify.equals("success")) {
    		Toast.makeText(context, "User created. You can log in.", Toast.LENGTH_LONG).show();
    		fSubmit.setVisibility(View.GONE);
    	} else {
    		// something wrong
    		Toast.makeText(context, notify, Toast.LENGTH_LONG).show();
    	}
    	
    }

}
