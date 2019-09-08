package fi.jamk.coolcalendar;

//import android.support.v7.app.ActionBarActivity;
//import android.app.ActionBar;
import fi.jamk.coolcalendar.CreateUserActivity;
import android.app.Activity;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Toast;


public class MainActivity extends Activity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.main, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_settings) {
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
    
	public void onClickSignUpButton(View view) {
		Intent intent = new Intent(MainActivity.this, CreateUserActivity.class);
		startActivity(intent);
	}

	public void onClickSignInButton(View view) {
		Intent intent = new Intent(MainActivity.this, LogInUserActivity.class);
		startActivity(intent);
	}
	
	public void onClickLoggedInButton(View view) {
		((MyStorage)MyStorage.getAppContext()).setUserId("546dea8cfb612d0002374d39");
		
		Intent intent = new Intent(MainActivity.this, UserInterfaceActivity.class);
		startActivity(intent);
	}

	public void onClickTestButton(View view) {
		//((MyStorage) getApplicationContext()).setUserId("abc");
		String s = ((MyStorage) getApplicationContext()).getUserId();
		if (s != null) {
			Toast.makeText(this, s, Toast.LENGTH_LONG).show();
		}
		/*MyStorage.setUserId("abcde");
		
		String s = ms.getUserId();
		Toast.makeText(this, s, Toast.LENGTH_LONG).show();*/
		
		/*SharedPreferences.Editor editor = getSharedPreferences("my_settings", MODE_PRIVATE).edit();
		editor.putString("name", "Mikko");
		editor.commit();
		
		SharedPreferences prefs = getSharedPreferences("my_settings", MODE_PRIVATE); 
		String restoredText = prefs.getString("name", "No name defined");
		Toast.makeText(this, restoredText, Toast.LENGTH_LONG).show();*/
	}


}
