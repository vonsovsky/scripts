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
import android.widget.TimePicker;
import android.widget.Toast;

public class CreateCourseActivity extends Activity {

	private EditText fName;
	private EditText fDay;
	private EditText fExtraInfo;
	private TimePicker tStart;
	private TimePicker tEnd;
	public static Button fSubmit;
	
	public static Context context;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_createcourse);

		context = this;
		initFields();
		initCallbacks();
		
		fSubmit.setVisibility(View.VISIBLE);
	}

	public void onClickCloseButton(View view) {
		finish();
	}
	
    private void initFields() {
    	fName = (EditText) findViewById(R.id.fName);
    	fDay = (EditText) findViewById(R.id.fDay);
    	fExtraInfo = (EditText) findViewById(R.id.fExtraInfo);
    	tStart = (TimePicker) findViewById(R.id.tStart);
    	tEnd = (TimePicker) findViewById(R.id.tEnd);
    	fSubmit = (Button) findViewById(R.id.fSubmit);
    }
    
    private void initCallbacks() {
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
    	
    	if (fName.getText().toString().length() == 0) {
    		fName.setError("Name is required.");
    		valid = false;
    	}
    	if (fDay.getText().toString().length() == 0) {
    		fDay.setError("Day is required.");
    		valid = false;
    	}
    	
    	return valid;
    }
    
    private void submit() {
    	if (validateFields()) {
    		String userId = ((MyStorage)MyStorage.getAppContext()).getUserId();
    		String retString =
		    	DBService.createCourse(userId, fName.getText().toString(), fDay.getText().toString(),
		    			fExtraInfo.getText().toString(), tStart.getCurrentHour() + ":" + tStart.getCurrentMinute(),
		    			tEnd.getCurrentHour() + ":" + tEnd.getCurrentMinute());
	    	if (retString != "") {
	    		Toast.makeText(this, retString, Toast.LENGTH_LONG).show();
	    	} else {
	    		Toast.makeText(this, "Please wait...", Toast.LENGTH_LONG).show();
	    	}
    	}
    }
    
    public static void httpCallback(String notify) {
    	if (notify.equals("success")) {
    		Toast.makeText(context, "Course created.", Toast.LENGTH_LONG).show();
    		fSubmit.setVisibility(View.GONE);
    	} else {
    		// something wrong
    		Toast.makeText(context, notify, Toast.LENGTH_LONG).show();
    	}
    	
    }

}
