using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Ticket_Drop : MonoBehaviour
{
    public float speed = 1;
    public float speed2 = 1;
    public Vector3 TargetPosition = new Vector3(100, 1, 1);
    public Vector3 TargetScale = new Vector3(2f, 2f, 1);

    private bool hasIEcalled = false;
    private bool falldown = false;

    private void Awake()
    {
        Time.timeScale = 0;
    }
    void Update()
    {
        
        //resizes and moves the ticket to create an illusion of falling onto screen
        if (falldown == false)
        {
            transform.position = Vector3.MoveTowards(transform.position, TargetPosition, speed); 
        }

        transform.localScale = Vector3.MoveTowards(transform.localScale, TargetScale, speed2);

        //Moves ticket off screen after its landed and waited
        if (falldown == true)
        {
            transform.position = Vector3.MoveTowards(transform.position, new Vector3(-30, 1, 1), 70 * Time.unscaledDeltaTime);
        }


        if (hasIEcalled == false)
        { StartCoroutine(MoveandPlay());
        }

        hasIEcalled = true;
    }

    IEnumerator MoveandPlay()
    {
        yield return new WaitForSecondsRealtime(2.8f);
        falldown = true;
        GameObject.Find("TicketGrey").GetComponent<Image>().enabled = false;
        Time.timeScale = 1;
        yield return new WaitForSecondsRealtime(2f);
        Destroy(gameObject);

    }
}