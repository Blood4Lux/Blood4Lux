package com.example.blood4lux.Activities;

import androidx.appcompat.app.AppCompatActivity;

import android.os.Bundle;
import android.content.Intent;
import android.util.Log;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import androidx.preference.PreferenceManager;
import com.android.volley.AuthFailureError;
import com.android.volley.Request.Method;
import com.android.volley.Response.ErrorListener;
import com.android.volley.Response.Listener;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.example.blood4lux.R;
import com.example.blood4lux.Utils.Endpoints;
import com.example.blood4lux.Utils.VolleySingleton;
import java.util.HashMap;
import java.util.Map;
import java.util.Objects;

public class LoginActivity extends AppCompatActivity {

    EditText NumberEt, PasswordEt;
    Button submit_button;
    TextView signUpText;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        NumberEt = findViewById(R.id.number);
        PasswordEt = findViewById(R.id.password);
        submit_button = findViewById(R.id.submit_button);
        signUpText = findViewById(R.id.sign_up_text);
        signUpText.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                startActivity(new Intent(LoginActivity.this, RegisterActivity.class));
            }
        });

        submit_button.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                NumberEt.setError(null);
                PasswordEt.setError(null);
                String number = NumberEt.getText().toString();
                String password = PasswordEt.getText().toString();
                if (isValid(number, password)) {
                    login(number, password);
                }
            }
        });
    }


    private void login(final String number, final String password) {
        StringRequest stringRequest = new StringRequest(
                Method.POST, Endpoints.login_url, new Listener<String>() {
            @Override
            public void onResponse(String response) {
                if (!response.equals("Invalid Credentials")) {
                    Toast.makeText(LoginActivity.this, response, Toast.LENGTH_SHORT).show();
                    startActivity(new Intent(LoginActivity.this, RegisterActivity.class));
                    PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).edit()
                            .putString("number", number).apply();
                    PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).edit()
                            .putString("city", response).apply();
                    LoginActivity.this.finish();
                } else {
                    Toast.makeText(LoginActivity.this, response, Toast.LENGTH_SHORT).show();
                }
            }
        }, new ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(LoginActivity.this, "Something went wrong:(", Toast.LENGTH_SHORT).show();
                Log.d("VOLLEY", Objects.requireNonNull(error.getMessage()));
            }
        }) {
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("password", password);
                params.put("number", number);
                return params;
            }
        };
        VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
    }


    private boolean isValid(String number, String password) {
        if (number.isEmpty()) {
            showMessage("Empty Mobile Number");
            NumberEt.setError("Empty Mobile Number");
            return false;
        } else if (password.isEmpty()) {
            showMessage("Empty Password");
            PasswordEt.setError("Empty Password");
            return false;
        }
        return true;
    }


    private void showMessage(String msg) {
        Toast.makeText(this, msg, Toast.LENGTH_SHORT).show();
    }


}
