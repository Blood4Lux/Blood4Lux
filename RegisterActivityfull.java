package com.example.blood4lux.Activities;

import android.content.Intent;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;
import android.widget.EditText;
import androidx.appcompat.app.AppCompatActivity;
import android.os.Bundle;
import android.widget.Toast;
import android.util.Log;
import androidx.preference.PreferenceManager;
import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.Request.Method;
import com.android.volley.Response.ErrorListener;
import com.android.volley.Response.Listener;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.example.blood4lux.Utils.Endpoints;
import com.example.blood4lux.Utils.VolleySingleton;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import com.example.blood4lux.R;

public class RegisterActivity extends AppCompatActivity {

    private EditText NameEt,  Blood_GroupEt, CommuneEt, Contact_NumberEt, PasswordEt;
    private Button submitButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_register);
        NameEt = findViewById(R.id.name);
        Blood_GroupEt = findViewById(R.id.blood_group);
        CommuneEt = findViewById(R.id.place);
        Contact_NumberEt = findViewById(R.id.number);
        PasswordEt = findViewById(R.id.password);
        submitButton = findViewById(R.id.submit_button);
        submitButton.setOnClickListener(new OnClickListener() {
            @Override
            public void onClick(View view) {
                String Name, Commune, Blood_Group, Password, Contact_Number;
                Name = NameEt.getText().toString();
                Commune = CommuneEt.getText().toString();
                Blood_Group = Blood_GroupEt.getText().toString();
                Password = PasswordEt.getText().toString();
                Contact_Number = Contact_NumberEt.getText().toString();
                showMessage(  Name+"/n"+Blood_Group+"/n"+Commune+"/n"+"/n"+Contact_Number+"/n"+Password);

                }
        });

    }
    private void register(final String Name, final String Commune, final String Blood_Group, final String Contact_Number, final String Password
                          ) {
        StringRequest stringRequest = new StringRequest(Method.POST, Endpoints.register_url, new Listener<String>() {
            @Override
            public void onResponse(String response) {
                if(response.equals("Success")){
                    PreferenceManager.getDefaultSharedPreferences(getApplicationContext()).edit()
                            .putString("Commune", Commune).apply();
                    Toast.makeText(RegisterActivity.this, response, Toast.LENGTH_SHORT).show();
                    startActivity(new Intent(RegisterActivity.this, com.example.blood4lux.Activites.MainActivity.class));
                    RegisterActivity.this.finish();
                }else{
                    Toast.makeText(RegisterActivity.this, response, Toast.LENGTH_SHORT).show();
                }
            }
        }, new ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                Toast.makeText(RegisterActivity.this, "Something went wrong:(", Toast.LENGTH_SHORT).show();
                Log.d("VOLLEY", error.getMessage());
            }
        }){
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> params = new HashMap<>();
                params.put("Name", Name);
                params.put("Blood_Group", Blood_Group);
                params.put("Commune", Commune);
                params.put("Contact_Number", Contact_Number);
                params.put("Password", Password);

                return params;
            }
        };
        VolleySingleton.getInstance(this).addToRequestQueue(stringRequest);
    }


    private boolean isValid(String Name, String Blood_Group, String Commune, String Password,
                            String Contact_Number) {
        List<String> valid_blood_groups = new ArrayList<>();
        valid_blood_groups.add("A+");
        valid_blood_groups.add("A-");
        valid_blood_groups.add("B+");
        valid_blood_groups.add("B-");
        valid_blood_groups.add("AB+");
        valid_blood_groups.add("AB-");
        valid_blood_groups.add("O+");
        valid_blood_groups.add("O-");
        if (Name.isEmpty()) {
            showMessage("Name is empty");
            return false;
        } else if (Commune.isEmpty()) {
            showMessage("Place name is required");
            return false;
        } else if (!valid_blood_groups.contains(Blood_Group)) {
            showMessage("Blood group invalid choose from " + valid_blood_groups);
            return false;
        } else if (Contact_Number.length() != 10) {
            showMessage("Invalid mobile number, number should be of 9 digits");
            return false;
        }
        return true;
    }


    private void showMessage(String msg) {
        Toast.makeText(this, msg, Toast.LENGTH_SHORT).show();
    }


}
